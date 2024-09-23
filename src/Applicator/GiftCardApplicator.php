<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Applicator;

use RuntimeException;
use Nextstore\SyliusGiftCardPlugin\Exception\ChannelMismatchException;
use Nextstore\SyliusGiftCardPlugin\Exception\GiftCardNotFoundException;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class GiftCardApplicator implements GiftCardApplicatorInterface
{
    private GiftCardRepositoryInterface $giftCardRepository;

    private OrderProcessorInterface $orderProcessor;

    public function __construct(
        GiftCardRepositoryInterface $giftCardRepository,
        OrderProcessorInterface $orderProcessor,
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->orderProcessor = $orderProcessor;
    }

    /**
     * @param string|GiftCardInterface $giftCard
     */
    public function apply(OrderInterface $order, $giftCard): void
    {
        if (is_string($giftCard)) {
            $giftCard = $this->getGiftCard($giftCard);
        }

        if (!$giftCard->isEnabled()) {
            throw new RuntimeException('The gift card is not enabled');
        }

        if ($giftCard->isExpired()) {
            throw new RuntimeException('The gift card is expired');
        }

        $orderChannel = $order->getChannel();
        if (null === $orderChannel) {
            throw new RuntimeException('The channel on the order cannot be null');
        }

        $giftCardChannel = $giftCard->getChannel();
        if (null === $giftCardChannel) {
            throw new RuntimeException('The channel on the gift card cannot be null');
        }

        if ($orderChannel->getCode() !== $giftCardChannel->getCode()) {
            throw new ChannelMismatchException($giftCardChannel, $orderChannel);
        }

        $order->addGiftCard($giftCard);

        $this->orderProcessor->process($order);
    }

    /**
     * @param string|GiftCardInterface $giftCard
     */
    public function remove(OrderInterface $order, $giftCard): void
    {
        if (is_string($giftCard)) {
            $giftCard = $this->getGiftCard($giftCard);
        }

        $order->removeGiftCard($giftCard);

        $this->orderProcessor->process($order);
    }

    private function getGiftCard(string $giftCardCode): GiftCardInterface
    {
        $giftCard = $this->giftCardRepository->findOneByCode($giftCardCode);

        if (null === $giftCard) {
            throw new GiftCardNotFoundException($giftCardCode);
        }

        return $giftCard;
    }
}
