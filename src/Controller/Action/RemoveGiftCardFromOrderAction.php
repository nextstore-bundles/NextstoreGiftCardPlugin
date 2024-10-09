<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Controller\Action;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Nextstore\SyliusGiftCardPlugin\Applicator\GiftCardApplicatorInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;
use Nextstore\SyliusGiftCardPlugin\Resolver\RedirectUrlResolverInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Webmozart\Assert\Assert;

final class RemoveGiftCardFromOrderAction
{
    use ORMManagerTrait;

    private CartContextInterface $cartContext;

    private GiftCardApplicatorInterface $giftCardApplicator;

    private RedirectUrlResolverInterface $redirectRouteResolver;

    public function __construct(
        CartContextInterface $cartContext,
        GiftCardApplicatorInterface $giftCardApplicator,
        RedirectUrlResolverInterface $redirectRouteResolver,
        ManagerRegistry $managerRegistry,
    ) {
        $this->cartContext = $cartContext;
        $this->giftCardApplicator = $giftCardApplicator;
        $this->redirectRouteResolver = $redirectRouteResolver;
        $this->managerRegistry = $managerRegistry;
    }

    public function __invoke(Request $request): Response
    {
        /** @var OrderInterface|null $order */
        $order = $this->cartContext->getCart();
        Assert::notNull($order);

        /** @var string|GiftCardInterface $giftCard */
        $giftCard = $request->attributes->get('giftCard');

        $this->giftCardApplicator->remove($order, $giftCard);

        $this->getManager($order)->flush();

        $session = $request->getSession();
        if ($session instanceof Session) {
            $session->getFlashBag()->add('success', 'nextstore_sylius_gift_card.gift_card_removed');
        }

        return new RedirectResponse($this->redirectRouteResolver->getUrlToRedirectTo($request, 'sylius_shop_cart_summary'));
    }
}
