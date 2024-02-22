<?php

namespace App\Service\Currency\Factory\Nbp;

use App\Entity\Currency\Currency;
use App\Service\NbpApi\DTO\ExchangeRate\ExchangeRateADTO;

interface CurrencyNbpFactoryInterface
{
    public function createFromExchangeRateADTO(ExchangeRateADTO $exchangeRateDTO): Currency;
}
