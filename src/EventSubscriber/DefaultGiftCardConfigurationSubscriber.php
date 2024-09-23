<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\EventSubscriber;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardConfigurationRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

/**
 * This subscriber makes sure that there is only one default gift card configuration at all times
 */
final class DefaultGiftCardConfigurationSubscriber implements EventSubscriberInterface
{
    private GiftCardConfigurationRepositoryInterface $giftCardConfigurationRepository;

    public function __construct(GiftCardConfigurationRepositoryInterface $giftCardConfigurationRepository)
    {
        $this->giftCardConfigurationRepository = $giftCardConfigurationRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'nextstore_sylius_gift_card.gift_card_configuration.pre_create' => 'handle',
            'nextstore_sylius_gift_card.gift_card_configuration.pre_update' => 'handle',
        ];
    }

    public function handle(ResourceControllerEvent $event): void
    {
        /** @var GiftCardConfigurationInterface|mixed $giftCardConfiguration */
        $giftCardConfiguration = $event->getSubject();
        Assert::isInstanceOf($giftCardConfiguration, GiftCardConfigurationInterface::class);

        if (!$giftCardConfiguration->isDefault()) {
            return;
        }

        /** @var GiftCardConfigurationInterface $existingGiftCardConfiguration */
        foreach ($this->giftCardConfigurationRepository->findAll() as $existingGiftCardConfiguration) {
            if ($giftCardConfiguration->getId() === $existingGiftCardConfiguration->getId()) {
                continue;
            }

            $existingGiftCardConfiguration->setDefault(false);
        }
    }
}
