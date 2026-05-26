<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Generator;

use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Webmozart\Assert\Assert;

final class GiftCardCodeGenerator implements GiftCardCodeGeneratorInterface
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    /** @var positive-int */
    private int $codeLength;

    public function __construct(
        private readonly GiftCardRepositoryInterface $giftCardRepository,
        int $codeLength,
    ) {
        Assert::greaterThan($codeLength, 0);

        $this->codeLength = $codeLength;
    }

    public function generate(): string
    {
        do {
            $code = $this->generateRandomCode();
        } while ($this->giftCardRepository->findOneByCode($code) !== null);

        return $code;
    }

    private function generateRandomCode(): string
    {
        $alphabetMax = strlen(self::ALPHABET) - 1;
        $code = '';

        for ($i = 0; $i < $this->codeLength; $i++) {
            $code .= self::ALPHABET[random_int(0, $alphabetMax)];
        }

        return $code;
    }
}
