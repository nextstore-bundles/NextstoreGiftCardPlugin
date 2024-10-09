<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Model;

use Sylius\Component\Core\Model\AdjustmentInterface as BaseAdjustmentInterface;

interface AdjustmentInterface extends BaseAdjustmentInterface
{
    public const ORDER_GIFT_CARD_ADJUSTMENT = 'order_gift_card';
}
