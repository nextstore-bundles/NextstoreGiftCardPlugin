<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin;

use Nextstore\SyliusGiftCardPlugin\DependencyInjection\Compiler\AddAdjustmentsToOrderAdjustmentClearerPass;
use Nextstore\SyliusGiftCardPlugin\DependencyInjection\Compiler\CreateServiceAliasesPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class NextstoreSyliusGiftCardPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    // public function getPath(): string
    // {
    //     return \dirname(__DIR__);
    // }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddAdjustmentsToOrderAdjustmentClearerPass());
        $container->addCompilerPass(new CreateServiceAliasesPass());
    }

    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }
}
