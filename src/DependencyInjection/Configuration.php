<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nextstore_sylius_gift_card');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
