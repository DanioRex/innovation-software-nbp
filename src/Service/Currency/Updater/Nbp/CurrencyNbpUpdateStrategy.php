<?php

declare(strict_types=1);

namespace App\Service\Currency\Updater\Nbp;

use App\Service\Currency\Updater\Nbp\Updater\CurrencyNbpUpdaterInterface;
use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;

final class CurrencyNbpUpdateStrategy implements CurrencyNbpUpdateStrategyInterface
{
    public function __construct(
        protected CurrencyNbpUpdaterInterface $updaterA,
    ) {
    }

    public function updateAllCurrencies(NbpApiTableTypeEnum $tableType): void
    {
        match ($tableType) {
            NbpApiTableTypeEnum::A => $this->updaterA->updateAllCurrencies(),
        };
    }
}
