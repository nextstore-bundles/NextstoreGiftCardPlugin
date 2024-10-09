<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Model;

use Sylius\Component\Core\Model\ImageInterface;

interface GiftCardConfigurationImageInterface extends ImageInterface
{
    public const TYPE_BACKGROUND = 'background';
}
