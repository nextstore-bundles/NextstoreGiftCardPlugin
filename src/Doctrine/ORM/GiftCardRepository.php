<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Nextstore\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Webmozart\Assert\Assert;

class GiftCardRepository extends EntityRepository implements GiftCardRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('customer')
            ->leftJoin('o.customer', 'customer')
        ;
    }

    public function findOneEnabledByCodeAndChannel(string $code, ChannelInterface $channel): ?GiftCardInterface
    {
        $giftCard = $this->findOneBy([
            'code' => $code,
            'channel' => $channel,
            'enabled' => true,
        ]);
        Assert::nullOrIsInstanceOf($giftCard, GiftCardInterface::class);

        return $giftCard;
    }

    public function findOneByCode(string $code): ?GiftCardInterface
    {
        $giftCard = $this->findOneBy([
            'code' => $code,
        ]);
        Assert::nullOrIsInstanceOf($giftCard, GiftCardInterface::class);

        return $giftCard;
    }

    public function findOneByOrderItemUnit(OrderItemUnitInterface $orderItemUnit): ?GiftCardInterface
    {
        $giftCard = $this->findOneBy([
            'orderItemUnit' => $orderItemUnit,
        ]);
        Assert::nullOrIsInstanceOf($giftCard, GiftCardInterface::class);

        return $giftCard;
    }

    public function findEnabled(): array
    {
        $giftCards = $this->findBy([
            'enabled' => true,
        ]);

        Assert::allIsInstanceOf($giftCards, GiftCardInterface::class);

        return $giftCards;
    }

    public function createAccountListQueryBuilder(CustomerInterface $customer): QueryBuilder
    {
        $qb = $this->createListQueryBuilder();
        $qb->andWhere('o.customer = :customer');
        $qb->setParameter('customer', $customer);

        return $qb;
    }

    public function findHighestCode(): ?string
    {
        // Only consider gift cards that were purchased through an order (order_item_unit_id IS NOT NULL)
        // This excludes connected cards from the main codebase that don't have an order association
        $conn = $this->getEntityManager()->getConnection();
        $tableName = $this->getClassMetadata()->getTableName();

        $sql = "SELECT code FROM {$tableName} WHERE order_item_unit_id IS NOT NULL ORDER BY CAST(code AS UNSIGNED) DESC LIMIT 1";
        $result = $conn->fetchOne($sql);

        return $result !== false ? (string) $result : null;
    }
}
