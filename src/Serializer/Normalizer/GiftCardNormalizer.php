<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Serializer\Normalizer;

use ArrayObject;
use Nextstore\SyliusGiftCardPlugin\Exception\UnexpectedTypeException;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Webmozart\Assert\Assert;

final class GiftCardNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $objectNormalizer;

    private MoneyFormatterInterface $moneyFormatter;

    public function __construct(ObjectNormalizer $objectNormalizer, MoneyFormatterInterface $moneyFormatter)
    {
        $this->objectNormalizer = $objectNormalizer;
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @param GiftCardInterface|mixed $object
     * @param string $format
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        Assert::isInstanceOf($object, GiftCardInterface::class);

        $data = $this->objectNormalizer->normalize($object, $format, $context);
        if (!is_array($data) && !$data instanceof ArrayObject) {
            throw new UnexpectedTypeException($data, 'array', ArrayObject::class);
        }

        if ($data instanceof ArrayObject) {
            $data = $data->getArrayCopy();
        }

        $localeCode = isset($context['localeCode']) && is_string($context['localeCode']) ? $context['localeCode'] : 'en_US';
        $data['amount'] = $this->moneyFormatter->format($object->getAmount(), (string) $object->getCurrencyCode(), $localeCode);

        return $data;
    }

    /**
     * @param mixed $data
     * @param string $format
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        $groups = (array) ($context['groups'] ?? []);

        return $data instanceof GiftCardInterface && in_array('nextstore:sylius-gift-card:render', $groups, true);
    }
}
