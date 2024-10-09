<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Factory;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface GiftCardConfigurationFactoryInterface extends FactoryInterface
{
    public function createNew(): GiftCardConfigurationInterface;
}
