<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Provider;

interface DefaultGiftCardTemplateContentProviderInterface
{
    public function getContent(): string;
}
