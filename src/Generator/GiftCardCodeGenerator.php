<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Generator;

use function preg_replace;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Webmozart\Assert\Assert;

final class GiftCardCodeGenerator implements GiftCardCodeGeneratorInterface
{
    private GiftCardRepositoryInterface $giftCardRepository;

    /** @var positive-int */
    private int $codeLength;

    /**
     * @param positive-int $codeLength
     */
    public function __construct(GiftCardRepositoryInterface $giftCardRepository, int $codeLength)
    {
        Assert::greaterThan($codeLength, 0);

        $this->giftCardRepository = $giftCardRepository;
        $this->codeLength = $codeLength;
    }

    public function generate(): string
    {
        // Calculate max value based on code length (10^n - 1)
        $maxValue = (int) str_repeat('9', $this->codeLength);

        do {
            $code = sprintf('%0' . $this->codeLength . 'd', mt_rand(1, $maxValue));
        } while ($this->exists($code));

        return $code;
    }

    private function exists(string $code): bool
    {
        return null !== $this->giftCardRepository->findOneByCode($code);
    }
}
