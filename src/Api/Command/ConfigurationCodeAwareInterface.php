<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\Command;

use Sylius\Bundle\ApiBundle\Command\CommandAwareDataTransformerInterface;

interface ConfigurationCodeAwareInterface extends CommandAwareDataTransformerInterface
{
    public function getConfigurationCode(): ?string;

    public function setConfigurationCode(?string $configurationCode): void;
}
