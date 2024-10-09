<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Twig\Extension;

use Nextstore\SyliusGiftCardPlugin\Factory\GiftCardFactoryInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Nextstore\SyliusGiftCardPlugin\Renderer\PdfRendererInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class PdfRuntime implements RuntimeExtensionInterface
{
    private PdfRendererInterface $PDFRenderer;

    private GiftCardFactoryInterface $giftCardFactory;

    public function __construct(
        PdfRendererInterface $giftCardPDFRenderer,
        GiftCardFactoryInterface $giftCardFactory,
    ) {
        $this->PDFRenderer = $giftCardPDFRenderer;
        $this->giftCardFactory = $giftCardFactory;
    }

    public function getBase64EncodedExamplePdfContent(GiftCardConfigurationInterface $giftCardChannelConfiguration): string
    {
        $giftCard = $this->giftCardFactory->createExample();

        return $this->PDFRenderer->render($giftCard, $giftCardChannelConfiguration)->getEncodedContent();
    }
}
