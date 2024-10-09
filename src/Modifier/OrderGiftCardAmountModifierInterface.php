<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Modifier;

use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;

interface OrderGiftCardAmountModifierInterface
{
    public function increment(OrderInterface $order): void;

    public function decrement(OrderInterface $order): void;
}
