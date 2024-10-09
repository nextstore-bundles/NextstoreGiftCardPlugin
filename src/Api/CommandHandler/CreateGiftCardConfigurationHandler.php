<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\CommandHandler;

use Doctrine\Persistence\ObjectManager;
use Nextstore\SyliusGiftCardPlugin\Api\Command\CreateGiftCardConfiguration;
use Nextstore\SyliusGiftCardPlugin\Factory\GiftCardConfigurationFactoryInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;

final class CreateGiftCardConfigurationHandler
{
    private GiftCardConfigurationFactoryInterface $giftCardConfigurationFactory;

    private ObjectManager $giftCardConfigurationManager;

    public function __construct(
        GiftCardConfigurationFactoryInterface $giftCardConfigurationFactory,
        ObjectManager $giftCardConfigurationManager,
    ) {
        $this->giftCardConfigurationFactory = $giftCardConfigurationFactory;
        $this->giftCardConfigurationManager = $giftCardConfigurationManager;
    }

    public function __invoke(CreateGiftCardConfiguration $command): GiftCardConfigurationInterface
    {
        $giftCardConfiguration = $this->giftCardConfigurationFactory->createNew();

        $giftCardConfiguration->setCode($command->code);
        $giftCardConfiguration->setEnabled($command->enabled);
        $giftCardConfiguration->setDefault($command->default);
        if (null !== $command->defaultValidityPeriod) {
            $giftCardConfiguration->setDefaultValidityPeriod($command->defaultValidityPeriod);
        }
        if (null !== $command->pageSize) {
            $giftCardConfiguration->setPageSize($command->pageSize);
        }
        if (null !== $command->orientation) {
            $giftCardConfiguration->setOrientation($command->orientation);
        }
        if (null !== $command->template) {
            $giftCardConfiguration->setTemplate($command->template);
        }

        $this->giftCardConfigurationManager->persist($giftCardConfiguration);

        return $giftCardConfiguration;
    }
}
