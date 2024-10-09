<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Form\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusGiftCardPlugin\Factory\GiftCardFactoryInterface;
use Nextstore\SyliusGiftCardPlugin\Form\Type\AddToCartGiftCardInformationType;
use Nextstore\SyliusGiftCardPlugin\Model\OrderItemUnitInterface;
use Nextstore\SyliusGiftCardPlugin\Model\ProductInterface;
use Nextstore\SyliusGiftCardPlugin\Order\AddToCartCommandInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Order\AddToCartType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

final class AddToCartTypeExtension extends AbstractTypeExtension
{
    private GiftCardFactoryInterface $giftCardFactory;

    private EntityManagerInterface $giftCardManager;

    public function __construct(
        GiftCardFactoryInterface $giftCardFactory,
        EntityManagerInterface $giftCardManager,
    ) {
        $this->giftCardFactory = $giftCardFactory;
        $this->giftCardManager = $giftCardManager;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            AddToCartType::class,
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'reworkFormForGiftCard']);

        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'populateCartItem']);
    }

    public function reworkFormForGiftCard(FormEvent $event): void
    {
        /** @var AddToCartCommandInterface|null $data */
        $data = $event->getData();
        if (null === $data) {
            return;
        }

        $variant = $data->getCartItem()->getVariant();
        if (null === $variant) {
            return;
        }

        /** @var ProductInterface|null $product */
        $product = $variant->getProduct();
        if (null === $product) {
            return;
        }

        // If the product is a gift card, we add the GiftCardInformation fields
        if ($product->isGiftCard()) {
            $form = $event->getForm();
            $form->add('giftCardInformation', AddToCartGiftCardInformationType::class, [
                'product' => $product,
            ]);
        }
    }

    public function populateCartItem(FormEvent $event): void
    {
        /** @var AddToCartCommandInterface|null $data */
        $data = $event->getData();
        if (null === $data) {
            return;
        }

        $cartItem = $data->getCartItem();
        /** @var ProductInterface|null $product */
        $product = $cartItem->getProduct();
        if (null === $product) {
            return;
        }

        if (!$product->isGiftCard()) {
            return;
        }

        $giftCardInformation = $data->getGiftCardInformation();
        if ($product->isGiftCardAmountConfigurable()) {
            $cartItem->setUnitPrice($giftCardInformation->getAmount());
            $cartItem->setImmutable(true);
        } else {
            $channel = $data->getCart()->getChannel();
            Assert::notNull($channel);
            $variant = $data->getCartItem()->getVariant();
            Assert::notNull($variant);
            $channelPricing = $variant->getChannelPricingForChannel($channel);
            Assert::notNull($channelPricing);
            $price = $channelPricing->getPrice();
            Assert::notNull($price);
            $cartItem->setUnitPrice($price);
        }

        $cart = $data->getCart();
        /** @var OrderItemUnitInterface $unit */
        foreach ($cartItem->getUnits() as $unit) {
            $giftCard = $this->giftCardFactory->createFromOrderItemUnitAndCart($unit, $cart);
            $giftCard->setCustomMessage($giftCardInformation->getCustomMessage());

            // As the common flow for any add to cart action will flush later. Do not flush here.
            $this->giftCardManager->persist($giftCard);
        }
    }
}
