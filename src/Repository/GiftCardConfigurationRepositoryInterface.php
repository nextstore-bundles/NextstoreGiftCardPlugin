<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Repository;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface GiftCardConfigurationRepositoryInterface extends RepositoryInterface
{
    public function findOneByChannelAndLocale(ChannelInterface $channel, LocaleInterface $locale): ?GiftCardConfigurationInterface;

    public function findDefault(): ?GiftCardConfigurationInterface;
}
