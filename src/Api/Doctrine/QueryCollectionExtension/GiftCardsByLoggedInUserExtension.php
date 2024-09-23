<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Api\Doctrine\QueryCollectionExtension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\ContextAwareQueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Nextstore\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class GiftCardsByLoggedInUserExtension implements ContextAwareQueryCollectionExtensionInterface
{
    private UserContextInterface $userContext;

    public function __construct(UserContextInterface $userContext)
    {
        $this->userContext = $userContext;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null,
        array $context = [],
    ): void {
        if (!is_a($resourceClass, GiftCardInterface::class, true)) {
            return;
        }

        $user = $this->userContext->getUser();

        if ($user instanceof AdminUserInterface) {
            return;
        }

        if (!$user instanceof ShopUserInterface) {
            throw new AccessDeniedException();
        }

        /** @var CustomerInterface $customer */
        $customer = $user->getCustomer();

        /** @var list<string> $rootAliases */
        $rootAliases = $queryBuilder->getRootAliases();

        $queryBuilder
            ->andWhere(sprintf('%s.customer = :customer', $rootAliases[0]))
            ->setParameter('customer', $customer->getId(), Types::INTEGER)
        ;
    }
}
