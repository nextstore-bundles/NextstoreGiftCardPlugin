<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\CommandHandler;

use Nextstore\SyliusGiftCardPlugin\Api\Command\AssociateConfigurationToChannel;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardChannelConfigurationInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class AssociateConfigurationToChannelHandler
{
    private RepositoryInterface $giftCardConfigurationRepository;

    private ChannelRepositoryInterface $channelRepository;

    private RepositoryInterface $localeRepository;

    private RepositoryInterface $giftCardChannelConfigurationRepository;

    private FactoryInterface $giftCardChannelConfigurationFactory;

    public function __construct(
        RepositoryInterface $giftCardConfigurationRepository,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $localeRepository,
        RepositoryInterface $giftCardChannelConfigurationRepository,
        FactoryInterface $giftCardChannelConfigurationFactory,
    ) {
        $this->giftCardConfigurationRepository = $giftCardConfigurationRepository;
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;
        $this->giftCardChannelConfigurationRepository = $giftCardChannelConfigurationRepository;
        $this->giftCardChannelConfigurationFactory = $giftCardChannelConfigurationFactory;
    }

    public function __invoke(AssociateConfigurationToChannel $command): GiftCardConfigurationInterface
    {
        Assert::notNull($command->getConfigurationCode(), 'GiftCardConfiguration code can not be null');

        /** @var GiftCardConfigurationInterface|null $configuration */
        $configuration = $this->giftCardConfigurationRepository->findOneBy(['code' => $command->getConfigurationCode()]);
        Assert::notNull($configuration, 'GiftCardConfiguration can not be null');

        $channel = $this->channelRepository->findOneByCode($command->channelCode);
        Assert::notNull($channel, 'Channel can not be null');

        /** @var LocaleInterface|null $locale */
        $locale = $this->localeRepository->findOneBy(['code' => $command->localeCode]);
        Assert::notNull($locale, 'Locale can not be null');

        /** @var GiftCardChannelConfigurationInterface|null $existingChannelConfiguration */
        $existingChannelConfiguration = $this->giftCardChannelConfigurationRepository->findOneBy([
            'configuration' => $configuration,
            'channel' => $channel,
            'locale' => $locale,
        ]);
        if (null !== $existingChannelConfiguration) {
            return $configuration;
        }

        /** @var GiftCardChannelConfigurationInterface $channelConfiguration */
        $channelConfiguration = $this->giftCardChannelConfigurationFactory->createNew();
        $channelConfiguration->setConfiguration($configuration);
        $channelConfiguration->setChannel($channel);
        $channelConfiguration->setLocale($locale);

        $configuration->addChannelConfiguration($channelConfiguration);

        return $configuration;
    }
}
