<?php

declare(strict_types=1);

namespace App\Service\NbpApi\DTO\ExchangeRate;

use PrinsFrank\Standards\Currency\CurrencyAlpha3;

abstract readonly class AbstractExchangeRateDTO
{
    public function __construct(
        public string $currency,
        public CurrencyAlpha3 $code,
    ) {
    }
}
