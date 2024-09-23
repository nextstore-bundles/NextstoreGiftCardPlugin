<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Provider;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;

final class OrderEligibleTotalProvider implements OrderEligibleTotalProviderInterface
{
    public function getEligibleTotal(OrderInterface $order, GiftCardInterface $giftCard): int
    {
        return $order->getTotal();
    }
}
