<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Factory;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Nextstore\SyliusGiftCardPlugin\Provider\DefaultGiftCardTemplateContentProviderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class GiftCardConfigurationFactory implements GiftCardConfigurationFactoryInterface
{
    private FactoryInterface $decoratedFactory;

    private DefaultGiftCardTemplateContentProviderInterface $defaultGiftCardTemplateContentProvider;

    private string $defaultOrientation;

    private string $defaultPageSize;

    public function __construct(
        FactoryInterface $decoratedFactory,
        DefaultGiftCardTemplateContentProviderInterface $defaultGiftCardTemplateContentProvider,
        string $defaultOrientation,
        string $defaultPageSize,
    ) {
        $this->decoratedFactory = $decoratedFactory;
        $this->defaultGiftCardTemplateContentProvider = $defaultGiftCardTemplateContentProvider;
        $this->defaultOrientation = $defaultOrientation;
        $this->defaultPageSize = $defaultPageSize;
    }

    public function createNew(): GiftCardConfigurationInterface
    {
        /** @var GiftCardConfigurationInterface $configuration */
        $configuration = $this->decoratedFactory->createNew();

        $configuration->setOrientation($this->defaultOrientation);
        $configuration->setPageSize($this->defaultPageSize);
        $configuration->setTemplate($this->defaultGiftCardTemplateContentProvider->getContent());

        return $configuration;
    }
}
