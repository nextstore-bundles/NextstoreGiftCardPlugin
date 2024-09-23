<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Form\Type;

use Nextstore\SyliusGiftCardPlugin\Provider\DatePeriodUnitProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class DatePeriodType extends AbstractType
{
    private DatePeriodUnitProviderInterface $datePeriodUnitProvider;

    public function __construct(DatePeriodUnitProviderInterface $datePeriodUnitProvider)
    {
        $this->datePeriodUnitProvider = $datePeriodUnitProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('value', IntegerType::class, [
            'label' => 'nextstore_sylius_gift_card.form.date_period.value',
        ]);
        $builder->add('unit', ChoiceType::class, [
            'label' => 'nextstore_sylius_gift_card.form.date_period.unit',
            'choices' => $this->datePeriodUnitProvider->getPeriodUnits(),
            'choice_label' => function (string $choice): string {
                return \sprintf('nextstore_sylius_gift_card.form.date_period.unit_%s', $choice);
            },
        ]);
    }
}
