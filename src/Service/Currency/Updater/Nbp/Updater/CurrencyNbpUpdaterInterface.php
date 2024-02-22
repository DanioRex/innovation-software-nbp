<?php

namespace App\Service\Currency\Updater\Nbp\Updater;

interface CurrencyNbpUpdaterInterface
{
    public function updateAllCurrencies(): void;
}
