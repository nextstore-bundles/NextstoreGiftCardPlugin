<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Resolver;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

interface CustomerChannelResolverInterface
{
    public function resolve(CustomerInterface $customer): ChannelInterface;
}
