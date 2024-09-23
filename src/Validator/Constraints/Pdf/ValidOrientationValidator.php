<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Validator\Constraints\Pdf;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ValidOrientationValidator extends ConstraintValidator
{
    private array $availableOrientations;

    public function __construct(array $availableOrientations)
    {
        $this->availableOrientations = $availableOrientations;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidOrientation) {
            throw new UnexpectedTypeException($constraint, ValidOrientation::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\in_array($value, $this->availableOrientations, true)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
