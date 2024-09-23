<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Provider;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;

interface OrderEligibleTotalProviderInterface
{
    public function getEligibleTotal(OrderInterface $order, GiftCardInterface $giftCard): int;
}
