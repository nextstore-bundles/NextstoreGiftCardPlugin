<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Form\Type;

use Nextstore\SyliusGiftCardPlugin\Controller\Action\AddGiftCardToOrderCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<AddGiftCardToOrderCommand>
 */
final class AddGiftCardToOrderType extends AbstractType
{
    private DataTransformerInterface $giftCardToCodeDataTransformer;

    private array $validationGroups;

    public function __construct(DataTransformerInterface $giftCardToCodeDataTransformer, array $validationGroups)
    {
        $this->giftCardToCodeDataTransformer = $giftCardToCodeDataTransformer;
        $this->validationGroups = $validationGroups;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('giftCard', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'nextstore_sylius_gift_card.ui.enter_gift_card_code',
                ],
                'invalid_message' => 'nextstore_sylius_gift_card.add_gift_card_to_order_command.gift_card.does_not_exist',
            ])
        ;

        $builder->get('giftCard')->addModelTransformer($this->giftCardToCodeDataTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddGiftCardToOrderCommand::class,
            'validation_groups' => $this->validationGroups,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'nextstore_sylius_gift_card_add_gift_card_to_order';
    }
}