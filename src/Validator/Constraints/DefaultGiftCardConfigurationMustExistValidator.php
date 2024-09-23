<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Validator\Constraints;

use Nextstore\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardConfigurationRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class DefaultGiftCardConfigurationMustExistValidator extends ConstraintValidator
{
    private GiftCardConfigurationRepositoryInterface $giftCardConfigurationRepository;

    public function __construct(GiftCardConfigurationRepositoryInterface $giftCardConfigurationRepository)
    {
        $this->giftCardConfigurationRepository = $giftCardConfigurationRepository;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DefaultGiftCardConfigurationMustExist) {
            throw new UnexpectedTypeException($constraint, DefaultGiftCardConfigurationMustExist::class);
        }

        if (!$value instanceof GiftCardConfigurationInterface) {
            throw new UnexpectedTypeException($value, GiftCardConfigurationInterface::class);
        }

        if ($value->isDefault()) {
            return;
        }

        $defaultGiftCardConfiguration = $this->giftCardConfigurationRepository->findDefault();
        if (null === $defaultGiftCardConfiguration || $defaultGiftCardConfiguration->getId() === $value->getId()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('default')
                ->addViolation()
            ;
        }
    }
}
