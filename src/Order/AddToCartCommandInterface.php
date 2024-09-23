<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Order;

use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface as BaseCommandInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

interface AddToCartCommandInterface extends BaseCommandInterface
{
    public function getCart(): OrderInterface;

    public function getCartItem(): OrderItemInterface;

    public function getGiftCardInformation(): GiftCardInformationInterface;
}
