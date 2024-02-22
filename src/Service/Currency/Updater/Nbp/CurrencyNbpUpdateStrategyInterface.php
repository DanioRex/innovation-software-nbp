<?php

namespace App\Service\Currency\Updater\Nbp;

use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;

interface CurrencyNbpUpdateStrategyInterface
{
    public function updateAllCurrencies(NbpApiTableTypeEnum $tableType): void;
}
