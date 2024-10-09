<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Form\Type\Rule;

use Symfony\Component\Form\AbstractType;

final class HasNoGiftCardConfigurationType extends AbstractType
{
    public function getName(): string
    {
        return 'nextstore_sylius_gift_card_plugin_promotion_rule_has_no_gift_card_configuration';
    }
}
