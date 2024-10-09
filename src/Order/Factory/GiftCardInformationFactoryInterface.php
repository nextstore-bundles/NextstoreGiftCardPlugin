<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Order\Factory;

use Nextstore\SyliusGiftCardPlugin\Order\GiftCardInformationInterface;
use Sylius\Component\Order\Model\OrderItemInterface;

interface GiftCardInformationFactoryInterface
{
    public function createNew(OrderItemInterface $orderItem): GiftCardInformationInterface;
}
