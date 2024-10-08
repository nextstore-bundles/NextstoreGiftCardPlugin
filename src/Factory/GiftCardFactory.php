<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Factory;

use DateTimeImmutable;
use DateTimeInterface;
use Nextstore\SyliusGiftCardPlugin\Generator\GiftCardCodeGeneratorInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderItemUnitInterface;
use Nextstore\SyliusGiftCardPlugin\Provider\GiftCardConfigurationProviderInterface;
use Sylius\Bundle\ShippingBundle\Provider\DateTimeProvider;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class GiftCardFactory implements GiftCardFactoryInterface
{
    public function __construct(
        private readonly FactoryInterface $decoratedFactory,
        private readonly GiftCardCodeGeneratorInterface $giftCardCodeGenerator,
        private readonly GiftCardConfigurationProviderInterface $giftCardConfigurationProvider,
        /** @psalm-suppress DeprecatedInterface */
        private readonly DateTimeProvider $dateTimeProvider,
        private readonly CurrencyContextInterface $currencyContext,
    ) {
    }

    public function createNew(): GiftCardInterface
    {
        /** @var GiftCardInterface $giftCard */
        $giftCard = $this->decoratedFactory->createNew();
        $giftCard->setCode($this->giftCardCodeGenerator->generate());

        return $giftCard;
    }

    public function createForChannel(ChannelInterface $channel): GiftCardInterface
    {
        $giftCard = $this->createNew();
        $giftCard->setChannel($channel);

        $channelConfiguration = $this->giftCardConfigurationProvider->getConfigurationForGiftCard($giftCard);
        $validityPeriod = $channelConfiguration->getDefaultValidityPeriod();
        if (null !== $validityPeriod) {
            $today = $this->dateTimeProvider->today();
            // Since the interface is types to DateTimeInterface, the modify method does not exist
            // whereas it does in DateTime and DateTimeImmutable
            Assert::isInstanceOf($today, DateTimeImmutable::class);
            /** @var DateTimeInterface $today */
            $today = $today->modify('+' . $validityPeriod);
            $giftCard->setExpiresAt($today);
        }

        return $giftCard;
    }

    public function createForChannelFromAdmin(ChannelInterface $channel): GiftCardInterface
    {
        $giftCard = $this->createForChannel($channel);
        $giftCard->setOrigin(GiftCardInterface::ORIGIN_ADMIN);

        return $giftCard;
    }

    public function createFromOrderItemUnit(OrderItemUnitInterface $orderItemUnit): GiftCardInterface
    {
        /** @var OrderInterface|null $order */
        $order = $orderItemUnit->getOrderItem()->getOrder();
        Assert::isInstanceOf($order, OrderInterface::class);

        /** @var CustomerInterface|null $customer */
        $customer = $order->getCustomer();
        Assert::isInstanceOf($customer, CustomerInterface::class);

        $giftCard = $this->createFromOrderItemUnitAndCart($orderItemUnit, $order);
        $giftCard->setCustomer($customer);
        $giftCard->setOrigin(GiftCardInterface::ORIGIN_ORDER);

        return $giftCard;
    }

    public function createFromOrderItemUnitAndCart(
        OrderItemUnitInterface $orderItemUnit,
        OrderInterface $cart,
    ): GiftCardInterface {
        $channel = $cart->getChannel();
        Assert::isInstanceOf($channel, ChannelInterface::class);
        $currencyCode = $cart->getCurrencyCode();
        Assert::notNull($currencyCode);

        $giftCard = $this->createForChannel($channel);
        $giftCard->setOrderItemUnit($orderItemUnit);
        $giftCard->setAmount($orderItemUnit->getTotal());
        $giftCard->setCurrencyCode($currencyCode);
        $giftCard->setChannel($channel);
        $giftCard->disable();
        $giftCard->setOrigin(GiftCardInterface::ORIGIN_ORDER);

        return $giftCard;
    }

    public function createExample(): GiftCardInterface
    {
        $giftCard = $this->createNew();
        $giftCard->setAmount(1500);
        $giftCard->setCurrencyCode($this->currencyContext->getCurrencyCode());
        $giftCard->setExpiresAt(new DateTimeImmutable('+3 years'));
        $giftCard->setCustomMessage('Hi there, beautiful! Thought I wanted to make your day even better with this gift card');

        return $giftCard;
    }
}
