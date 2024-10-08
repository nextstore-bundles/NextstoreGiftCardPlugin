<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Controller\Action;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardBalanceCollection;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * The purpose of this class is to show the gift card balance, i.e. what amount is still available on enabled gift cards
 */
final class GiftCardBalanceAction
{
    private GiftCardRepositoryInterface $giftCardRepository;

    private Environment $twig;

    public function __construct(
        GiftCardRepositoryInterface $giftCardRepository,
        Environment $twig,
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        $giftCardBalanceCollection = GiftCardBalanceCollection::createFromGiftCards(
            $this->giftCardRepository->findEnabled(),
        );

        return new Response($this->twig->render('@NextstoreSyliusGiftCardPlugin/Admin/giftCardBalance.html.twig', [
            'giftCardBalanceCollection' => $giftCardBalanceCollection,
        ]));
    }
}
