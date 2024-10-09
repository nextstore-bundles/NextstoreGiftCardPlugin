<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Webmozart\Assert\Assert;

final class GiftCardDataPersister implements ContextAwareDataPersisterInterface
{
    private ContextAwareDataPersisterInterface $decoratedDataPersister;

    public function __construct(ContextAwareDataPersisterInterface $decoratedDataPersister)
    {
        $this->decoratedDataPersister = $decoratedDataPersister;
    }

    /**
     * @param mixed $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof GiftCardInterface;
    }

    /**
     * @param GiftCardInterface|mixed $data
     *
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        Assert::isInstanceOf($data, GiftCardInterface::class);

        $data->setOrigin(GiftCardInterface::ORIGIN_API);

        return $this->decoratedDataPersister->persist($data, $context);
    }

    /**
     * @param GiftCardInterface|mixed $data
     *
     * @return mixed
     */
    public function remove($data, array $context = [])
    {
        return $this->decoratedDataPersister->remove($data, $context);
    }
}
