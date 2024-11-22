<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\Command;

use Sylius\Bundle\ApiBundle\Command\Cart\AddItemToCart as BaseAddItemToCart;

class AddItemToCart extends BaseAddItemToCart
{
    protected ?int $amount;

    protected ?string $customMessage;

    protected ?string $receiverName;

    protected ?string $receiverEmail;

    protected ?string $senderName;

    protected bool $sendToReceiver = false;

    public function __construct(
        string $productVariantCode,
        int $quantity,
        int $amount = null,
        string $customMessage = null,
        string $receiverName = null,
        string $receiverEmail = null,
        string $senderName = null,
        bool $sendToReceiver = false
    )
    {
        parent::__construct($productVariantCode, $quantity);

        $this->amount = $amount;
        $this->customMessage = $customMessage;
        $this->receiverName = $receiverName;
        $this->receiverEmail = $receiverEmail;
        $this->senderName = $senderName;
        $this->sendToReceiver = $sendToReceiver;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getCustomMessage(): ?string
    {
        return $this->customMessage;
    }

    public function getReceiverName(): ?string
    {
        return $this->receiverName;
    }

    public function getReceiverEmail(): ?string
    {
        return $this->receiverEmail;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function getSendToReceiver(): bool
    {
        return $this->sendToReceiver;
    }
}
