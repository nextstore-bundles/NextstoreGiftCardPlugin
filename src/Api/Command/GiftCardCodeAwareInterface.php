<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\Command;

use Sylius\Bundle\ApiBundle\Command\CommandAwareDataTransformerInterface;

interface GiftCardCodeAwareInterface extends CommandAwareDataTransformerInterface
{
    public function getGiftCardCode(): ?string;

    public function setGiftCardCode(?string $giftCardCode): void;
}
