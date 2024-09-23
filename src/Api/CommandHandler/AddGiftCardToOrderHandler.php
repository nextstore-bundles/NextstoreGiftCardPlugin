<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\CommandHandler;

use Nextstore\SyliusGiftCardPlugin\Api\Command\AddGiftCardToOrder;
use Nextstore\SyliusGiftCardPlugin\Applicator\GiftCardApplicatorInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Nextstore\SyliusGiftCardPlugin\Repository\OrderRepositoryInterface;
use Webmozart\Assert\Assert;

final class AddGiftCardToOrderHandler
{
    private GiftCardRepositoryInterface $giftCardRepository;

    private OrderRepositoryInterface $orderRepository;

    private GiftCardApplicatorInterface $giftCardApplicator;

    public function __construct(
        GiftCardRepositoryInterface $giftCardRepository,
        OrderRepositoryInterface $orderRepository,
        GiftCardApplicatorInterface $giftCardApplicator,
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->orderRepository = $orderRepository;
        $this->giftCardApplicator = $giftCardApplicator;
    }

    public function __invoke(AddGiftCardToOrder $command): GiftCardInterface
    {
        $giftCardCode = $command->getGiftCardCode();
        Assert::notNull($giftCardCode);

        $giftCard = $this->giftCardRepository->findOneByCode($giftCardCode);
        Assert::notNull($giftCard);

        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->findOneBy(['tokenValue' => $command->orderTokenValue]);
        Assert::notNull($order);

        $this->giftCardApplicator->apply($order, $giftCard);

        return $giftCard;
    }
}
