<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Modifier;

use Doctrine\Persistence\ObjectManager;
use RuntimeException;
use Nextstore\SyliusGiftCardPlugin\Model\AdjustmentInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;
use function sprintf;

/**
 * This class' responsibility is to modify the amount on a gift card when it's used to pay for an order
 */
final class OrderGiftCardAmountModifier implements OrderGiftCardAmountModifierInterface
{
    private ObjectManager $giftCardManager;

    public function __construct(ObjectManager $giftCardManager)
    {
        $this->giftCardManager = $giftCardManager;
    }

    public function decrement(OrderInterface $order): void
    {
        foreach ($order->getAdjustments(AdjustmentInterface::ORDER_GIFT_CARD_ADJUSTMENT) as $adjustment) {
            $giftCard = self::getGiftCard($order, (string) $adjustment->getOriginCode());

            if ($giftCard->isPercentGiftCard()) {
                $giftCard->disable();
            } else {
                $amount = abs($adjustment->getAmount());

                if ($amount >= $giftCard->getAmount()) {
                    $giftCard->disable();
                    $giftCard->setAmount(0);
                }

                if ($amount < $giftCard->getAmount()) {
                    $giftCard->enable();

                    $giftCard->setAmount($giftCard->getAmount() - $amount);
                }
            }
        }

        $this->giftCardManager->flush();
    }

    public function increment(OrderInterface $order): void
    {
        foreach ($order->getAdjustments(AdjustmentInterface::ORDER_GIFT_CARD_ADJUSTMENT) as $adjustment) {
            $giftCard = self::getGiftCard($order, (string) $adjustment->getOriginCode());
            if (!$giftCard->isPercentGiftCard()) {
                $giftCard->setAmount($giftCard->getAmount() + abs($adjustment->getAmount()));

                if ($giftCard->getAmount() > 0) {
                    $giftCard->enable();
                }
            } else {
                $giftCard->enable();
            }
        }

        $this->giftCardManager->flush();
    }

    private static function getGiftCard(OrderInterface $order, string $code): GiftCardInterface
    {
        foreach ($order->getGiftCards() as $giftCard) {
            if ($giftCard->getCode() === $code) {
                return $giftCard;
            }
        }

        throw new RuntimeException(sprintf('The order %s does not have a gift card with code %s', (string) $order->getNumber(), $code));
    }
}
