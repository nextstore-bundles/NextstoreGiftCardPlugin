<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Resolver;

use Symfony\Component\HttpFoundation\Request;

interface RedirectUrlResolverInterface
{
    public function getUrlToRedirectTo(Request $request, string $defaultRoute): string;
}
