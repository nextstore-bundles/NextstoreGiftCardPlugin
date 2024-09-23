<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Validator\Constraints\Pdf;

use Symfony\Component\Validator\Constraint;

final class ValidOrientation extends Constraint
{
    public string $message = 'nextstore_sylius_gift_card.gift_card_configuration.orientation_not_valid';
}
