<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class GiftCardConfigurationFixture extends AbstractResourceFixture
{
    public function getName(): string
    {
        return 'nextstore_gift_card_configuration';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->scalarNode('background_image')->cannotBeEmpty()->end()
                ->booleanNode('enabled')->end()
                ->booleanNode('default')->end()
        ;
    }
}
