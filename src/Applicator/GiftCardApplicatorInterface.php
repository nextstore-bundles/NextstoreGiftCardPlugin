<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Applicator;

use Nextstore\SyliusGiftCardPlugin\Exception\ChannelMismatchException;
use Nextstore\SyliusGiftCardPlugin\Exception\GiftCardNotFoundException;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;

interface GiftCardApplicatorInterface
{
    /**
     * Applies $giftCard to $order
     *
     * @param string|GiftCardInterface $giftCard
     *
     * @throws GiftCardNotFoundException if gift card is not found
     * @throws ChannelMismatchException if the orders channel does not match the gift cards channel
     */
    public function apply(OrderInterface $order, $giftCard): void;

    /**
     * @param string|GiftCardInterface $giftCard
     */
    public function remove(OrderInterface $order, $giftCard): void;
}
