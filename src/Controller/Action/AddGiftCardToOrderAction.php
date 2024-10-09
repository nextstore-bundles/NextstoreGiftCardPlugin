<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Controller\Action;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Nextstore\SyliusGiftCardPlugin\Applicator\GiftCardApplicatorInterface;
use Nextstore\SyliusGiftCardPlugin\Form\Type\AddGiftCardToOrderType;
use Nextstore\SyliusGiftCardPlugin\Model\OrderInterface;
use Nextstore\SyliusGiftCardPlugin\Resolver\RedirectUrlResolverInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class AddGiftCardToOrderAction
{
    use ORMManagerTrait;

    private FormFactoryInterface $formFactory;

    private CartContextInterface $cartContext;

    private GiftCardApplicatorInterface $giftCardApplicator;

    private RedirectUrlResolverInterface $redirectRouteResolver;

    private Environment $twig;

    public function __construct(
        FormFactoryInterface $formFactory,
        CartContextInterface $cartContext,
        GiftCardApplicatorInterface $giftCardApplicator,
        RedirectUrlResolverInterface $redirectRouteResolver,
        Environment $twig,
        ManagerRegistry $managerRegistry,
    ) {
        $this->formFactory = $formFactory;
        $this->cartContext = $cartContext;
        $this->giftCardApplicator = $giftCardApplicator;
        $this->redirectRouteResolver = $redirectRouteResolver;
        $this->twig = $twig;
        $this->managerRegistry = $managerRegistry;
    }

    public function __invoke(Request $request): Response
    {
        /** @var OrderInterface|null $order */
        $order = $this->cartContext->getCart();

        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $addGiftCardToOrderCommand = new AddGiftCardToOrderCommand();
        $form = $this->formFactory->create(AddGiftCardToOrderType::class, $addGiftCardToOrderCommand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $giftCard = $addGiftCardToOrderCommand->getGiftCard();
            Assert::notNull($giftCard);
            $this->giftCardApplicator->apply($order, $giftCard);

            $this->getManager($order)->flush();

            $session = $request->getSession();
            if ($session instanceof Session) {
                $session->getFlashBag()->add('success', 'nextstore_sylius_gift_card.gift_card_added');
            }

            return new RedirectResponse($this->redirectRouteResolver->getUrlToRedirectTo($request, 'sylius_shop_cart_summary'));
        }

        return new Response($this->twig->render('@SetonoSyliusGiftCardPlugin/Shop/addGiftCardToOrder.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
