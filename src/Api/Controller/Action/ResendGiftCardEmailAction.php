<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\Controller\Action;

use Nextstore\SyliusGiftCardPlugin\EmailManager\GiftCardEmailManagerInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ResendGiftCardEmailAction
{
    private GiftCardEmailManagerInterface $giftCardEmailManager;

    public function __construct(GiftCardEmailManagerInterface $giftCardEmailManager)
    {
        $this->giftCardEmailManager = $giftCardEmailManager;
    }

    public function __invoke(GiftCardInterface $data): Response
    {
        if (($order = $data->getOrder()) !== null) {
            $this->giftCardEmailManager->sendEmailWithGiftCardsFromOrder($order, [$data]);
        } elseif (($customer = $data->getCustomer()) !== null) {
            $this->giftCardEmailManager->sendEmailToCustomerWithGiftCard($customer, $data);
        } else {
            throw new BadRequestHttpException();
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
