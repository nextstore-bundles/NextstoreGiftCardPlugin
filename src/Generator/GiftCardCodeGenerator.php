<?php
declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Generator;

use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Webmozart\Assert\Assert;

final class GiftCardCodeGenerator implements GiftCardCodeGeneratorInterface
{
    private GiftCardRepositoryInterface $giftCardRepository;

    /** @var positive-int */
    private int $codeLength;

    /** @var positive-int */
    private int $startingCode;

    /**
     * @param positive-int $codeLength
     * @param positive-int $startingCode
     */
    public function __construct(GiftCardRepositoryInterface $giftCardRepository, int $codeLength, int $startingCode)
    {
        Assert::greaterThan($codeLength, 0);
        Assert::greaterThan($startingCode, 0);

        $this->giftCardRepository = $giftCardRepository;
        $this->codeLength = $codeLength;
        $this->startingCode = $startingCode;
    }

    public function generate(): string
    {
        // Get the next available sequential code
        $nextCode = $this->getNextSequentialCode();

        // Format with leading zeros based on code length
        return sprintf('%0' . $this->codeLength . 'd', $nextCode);
    }

    private function getNextSequentialCode(): int
    {
        // Get the highest existing code from the database
        $highestCode = $this->giftCardRepository->findHighestCode();

        if ($highestCode === null) {
            // No codes exist yet, start from the starting code
            return $this->startingCode;
        }

        if ($highestCode < $this->startingCode) {
            return $this->startingCode;
        }

        // Convert highest code to integer and increment
        $highestCodeInt = (int) $highestCode;
        $nextCode = max($highestCodeInt + 1, $this->startingCode);

        // Calculate max value based on code length
        $maxValue = (int) str_repeat('9', $this->codeLength);

        if ($nextCode > $maxValue) {
            throw new \RuntimeException(
                sprintf('Cannot generate code: reached maximum value (%d) for code length %d', $maxValue, $this->codeLength)
            );
        }

        return $nextCode;
    }

    private function exists(string $code): bool
    {
        return null !== $this->giftCardRepository->findOneByCode($code);
    }
}
