<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Provider;

interface DatePeriodUnitProviderInterface
{
    public function getPeriodUnits(): array;
}
