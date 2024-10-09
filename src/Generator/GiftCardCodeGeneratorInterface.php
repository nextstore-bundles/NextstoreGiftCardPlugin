<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Generator;

interface GiftCardCodeGeneratorInterface
{
    public function generate(): string;
}
