<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\EmailManager;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface GiftCardEmailManagerInterface
{
    public function sendEmailToCustomerWithGiftCard(CustomerInterface $customer, GiftCardInterface $giftCard): void;

    /**
     * @param list<GiftCardInterface> $giftCards
     */
    public function sendEmailWithGiftCardsFromOrder(OrderInterface $order, array $giftCards): void;
}
