<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Factory;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface GiftCardFactoryInterface extends FactoryInterface
{
    public function createNew(): GiftCardInterface;

    public function createForChannel(ChannelInterface $channel): GiftCardInterface;

    public function createForChannelFromAdmin(ChannelInterface $channel): GiftCardInterface;

    public function createFromOrderItemUnit(OrderItemUnitInterface $orderItemUnit): GiftCardInterface;

    public function createFromOrderItemUnitAndCart(
        OrderItemUnitInterface $orderItemUnit,
        OrderInterface $cart,
    ): GiftCardInterface;

    /**
     * Create an example GiftCard that is used to generate the example PDF for configuration live rendering
     */
    public function createExample(): GiftCardInterface;
}
