<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Order;

interface GiftCardInformationInterface
{
    public function getAmount(): int;

    public function setAmount(int $amount): void;

    public function getCustomMessage(): ?string;

    public function setCustomMessage(?string $customMessage): void;
}
