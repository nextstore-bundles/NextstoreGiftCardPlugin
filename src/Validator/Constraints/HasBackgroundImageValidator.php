<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Validator\Constraints;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class HasBackgroundImageValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof HasBackgroundImage) {
            throw new UnexpectedTypeException($constraint, HasBackgroundImage::class);
        }

        if (!$value instanceof GiftCardConfigurationInterface) {
            return;
        }

        $backgroundImage = $value->getBackgroundImage();

        if (null === $backgroundImage) {
            return;
        }

        if (null === $backgroundImage->getFile() && null === $backgroundImage->getPath()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
