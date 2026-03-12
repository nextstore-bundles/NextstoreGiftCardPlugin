<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Operator;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusGiftCardPlugin\EmailManager\GiftCardEmailManagerInterface;
use Nextstore\SyliusGiftCardPlugin\Factory\GiftCardFactoryInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderItemUnitInterface;
use Nextstore\SyliusGiftCardPlugin\Model\ProductInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;

/**
 * This class' responsibility is to operate on gift cards bought on an order
 * It does NOT handle gift cards used to buy an order
 */
final class OrderGiftCardOperator implements OrderGiftCardOperatorInterface
{
    private EntityManagerInterface $giftCardManager;

    private GiftCardEmailManagerInterface $giftCardOrderEmailManager;

    private GiftCardFactoryInterface $giftCardFactory;

    public function __construct(
        EntityManagerInterface $giftCardManager,
        GiftCardEmailManagerInterface $giftCardOrderEmailManager,
        GiftCardFactoryInterface $giftCardFactory,
    ) {
        $this->giftCardManager = $giftCardManager;
        $this->giftCardOrderEmailManager = $giftCardOrderEmailManager;
        $this->giftCardFactory = $giftCardFactory;
    }

    public function associateToCustomer(OrderInterface $order): void
    {
        $items = self::getOrderItemsThatAreGiftCards($order);

        if (count($items) === 0) {
            return;
        }

        /** @var CustomerInterface|null $customer */
        $customer = $order->getCustomer();
        Assert::isInstanceOf($customer, CustomerInterface::class);

        foreach ($items as $item) {
            // Find an existing gift card on this item to use as template for receiver info
            $templateGiftCard = null;
            /** @var OrderItemUnitInterface $unit */
            foreach ($item->getUnits() as $unit) {
                if ($unit->getGiftCard() !== null) {
                    $templateGiftCard = $unit->getGiftCard();
                    break;
                }
            }

            /** @var OrderItemUnitInterface $unit */
            foreach ($item->getUnits() as $unit) {
                $giftCard = $unit->getGiftCard();

                // Create gift card for units added via quantity increase (they have no gift card yet)
                if ($giftCard === null) {
                    $giftCard = $this->giftCardFactory->createFromOrderItemUnitAndCart($unit, $order);
                    if ($templateGiftCard !== null) {
                        $giftCard->setCustomMessage($templateGiftCard->getCustomMessage());
                        $giftCard->setReceiverName($templateGiftCard->getReceiverName());
                        $giftCard->setReceiverEmail($templateGiftCard->getReceiverEmail());
                        $giftCard->setSenderName($templateGiftCard->getSenderName());
                        $giftCard->setSendToReceiver($templateGiftCard->getSendToReceiver());
                    }
                    $this->giftCardManager->persist($giftCard);
                }

                if ($giftCard->getReceiverName() === null && $giftCard->getReceiverEmail() === null) {
                    $giftCard->setCustomer($customer);
                } else {
                    $receiver = $this->giftCardManager->getRepository(CustomerInterface::class)->findOneBy(['email' => $giftCard->getReceiverEmail()]);

                    if ($receiver !== null) {
                        $giftCard->setCustomer($receiver);
                    }
                }
            }
        }

        $this->giftCardManager->flush();
    }

    public function enable(OrderInterface $order): void
    {
        $giftCards = $this->getGiftCards($order);

        if (count($giftCards) === 0) {
            return;
        }

        foreach ($giftCards as $giftCard) {
            $giftCard->enable();
        }

        $this->giftCardManager->flush();
    }

    /**
     * Calls when Order this GiftCardCode was bought at
     * become cancelled
     */
    public function disable(OrderInterface $order): void
    {
        $giftCards = $this->getGiftCards($order);

        if (count($giftCards) === 0) {
            return;
        }

        foreach ($giftCards as $giftCard) {
            $giftCard->disable();
        }

        $this->giftCardManager->flush();
    }

    public function send(OrderInterface $order): void
    {
        $giftCards = $this->getGiftCards($order);

        if (count($giftCards) === 0) {
            return;
        }

        $receiverGiftCards = [];
        $customerGiftCards = [];

        foreach ($giftCards as $giftCard) {
            if ($giftCard->getSendToReceiver() && $giftCard->getReceiverEmail() !== null) {
                $receiverGiftCards[] = $giftCard;
            } else {
                $customerGiftCards[] = $giftCard;
            }
        }

        if (count($receiverGiftCards) > 0) {
            $this->giftCardOrderEmailManager->sendEmailToReceiverWithGiftCardsFromOrder($order, $receiverGiftCards);
        }

        if (count($customerGiftCards) > 0) {
            $this->giftCardOrderEmailManager->sendEmailWithGiftCardsFromOrder($order, $customerGiftCards);
        }
    }

    public function sendToReceiver(OrderInterface $order, array $giftCards): void
    {
        $this->giftCardOrderEmailManager->sendEmailToReceiverWithGiftCardsFromOrder($order, $giftCards);
    }

    /**
     * Returns all the gift cards that were bought on the given order
     *
     * @return list<GiftCardInterface>
     */
    private function getGiftCards(OrderInterface $order): array
    {
        $giftCards = [];

        $items = self::getOrderItemsThatAreGiftCards($order);
        foreach ($items as $item) {
            /** @var OrderItemUnitInterface $unit */
            foreach ($item->getUnits() as $unit) {
                $giftCard = $unit->getGiftCard();
                if (null === $giftCard) {
                    continue;
                }

                $giftCards[] = $giftCard;
            }
        }

        return $giftCards;
    }

    /**
     * @return Collection<array-key, OrderItemInterface>
     */
    private static function getOrderItemsThatAreGiftCards(OrderInterface $order): Collection
    {
        return $order->getItems()->filter(static function (OrderItemInterface $item): bool {
            /** @var ProductInterface|null $product */
            $product = $item->getProduct();

            Assert::isInstanceOf($product, ProductInterface::class);

            return $product->isGiftCard();
        });
    }
}
