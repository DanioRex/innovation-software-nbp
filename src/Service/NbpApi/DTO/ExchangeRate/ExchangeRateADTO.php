<?php

declare(strict_types=1);

namespace App\Service\NbpApi\DTO\ExchangeRate;

use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Symfony\Component\Validator\Constraints\GreaterThan;

final readonly class ExchangeRateADTO extends AbstractExchangeRateDTO
{
    public function __construct(
        #[GreaterThan(0)]
        public float $mid,

        string $currency,
        CurrencyAlpha3 $code,
    ) {
        parent::__construct(
            currency: $currency,
            code: $code,
        );
    }
}
