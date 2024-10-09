<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Model;

use Doctrine\ORM\Mapping as ORM;

trait OrderItemUnitTrait
{
    /** @ORM\OneToOne (targetEntity="Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface", mappedBy="orderItemUnit") */
    protected ?GiftCardInterface $giftCard = null;

    public function getGiftCard(): ?GiftCardInterface
    {
        return $this->giftCard;
    }

    public function setGiftCard(GiftCardInterface $giftCard): void
    {
        if ($this->giftCard === $giftCard) {
            return;
        }

        $this->giftCard = $giftCard;

        $giftCard->setOrderItemUnit($this);
    }
}