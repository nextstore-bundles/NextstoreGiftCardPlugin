<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Order\Factory;

use Nextstore\SyliusGiftCardPlugin\Order\GiftCardInformationInterface;
use Sylius\Component\Order\Model\OrderItemInterface;

final class GiftCardInformationFactory implements GiftCardInformationFactoryInterface
{
    /** @var class-string<GiftCardInformationInterface> */
    private string $className;

    /**
     * @param class-string<GiftCardInformationInterface> $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function createNew(OrderItemInterface $orderItem): GiftCardInformationInterface
    {
        return new $this->className($orderItem->getUnitPrice());
    }
}
