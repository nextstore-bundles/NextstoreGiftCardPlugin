<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Controller\Action\Admin;

use Nextstore\SyliusGiftCardPlugin\Factory\GiftCardFactoryInterface;
use Nextstore\SyliusGiftCardPlugin\Form\Type\GiftCardConfigurationType;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Nextstore\SyliusGiftCardPlugin\Renderer\PdfRendererInterface;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardConfigurationRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class GenerateEncodedExamplePdfAction
{
    private GiftCardFactoryInterface $giftCardFactory;

    private GiftCardConfigurationRepositoryInterface $giftCardConfigurationRepository;

    private PdfRendererInterface $pdfRenderer;

    private FormFactoryInterface $formFactory;

    public function __construct(
        GiftCardFactoryInterface $giftCardFactory,
        GiftCardConfigurationRepositoryInterface $giftCardConfigurationRepository,
        PdfRendererInterface $giftCardPDFRenderer,
        FormFactoryInterface $formFactory,
    ) {
        $this->giftCardFactory = $giftCardFactory;
        $this->giftCardConfigurationRepository = $giftCardConfigurationRepository;
        $this->pdfRenderer = $giftCardPDFRenderer;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, int $id): Response
    {
        $giftCard = $this->giftCardFactory->createExample();

        /** @var GiftCardConfigurationInterface|null $giftCardConfiguration */
        $giftCardConfiguration = $this->giftCardConfigurationRepository->find($id);
        Assert::isInstanceOf($giftCardConfiguration, GiftCardConfigurationInterface::class);

        $form = $this->formFactory->create(GiftCardConfigurationType::class, $giftCardConfiguration);
        $form->handleRequest($request);

        return new Response($this->pdfRenderer->render($giftCard, $giftCardConfiguration)->getEncodedContent());
    }
}
