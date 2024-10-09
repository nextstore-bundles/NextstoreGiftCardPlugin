<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Controller\Action;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;

final class SearchGiftCardCommand
{
    private ?GiftCardInterface $giftCard = null;

    public function getGiftCard(): ?GiftCardInterface
    {
        return $this->giftCard;
    }

    public function setGiftCard(?GiftCardInterface $giftCard): void
    {
        $this->giftCard = $giftCard;
    }
}
