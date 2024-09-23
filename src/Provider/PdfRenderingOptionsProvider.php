<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Provider;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;

final class PdfRenderingOptionsProvider implements PdfRenderingOptionsProviderInterface
{
    public function getRenderingOptions(GiftCardConfigurationInterface $giftCardConfiguration): array
    {
        $options = [];
        $pageSize = $giftCardConfiguration->getPageSize();
        if (null !== $pageSize) {
            $options['page-size'] = $pageSize;
        }

        $orientation = $giftCardConfiguration->getOrientation();
        if (null !== $orientation) {
            $options['orientation'] = $orientation;
        }

        return $options;
    }
}
