<?php

declare(strict_types=1);

namespace App\Service\Currency\Factory\Nbp;

use App\Entity\Currency\Currency;
use App\Service\Currency\Builder\CurrencyBuilderInterface;
use App\Service\NbpApi\DTO\ExchangeRate\ExchangeRateADTO;

class CurrencyNbpFactory implements CurrencyNbpFactoryInterface
{
    public function __construct(
        protected CurrencyBuilderInterface $builder,
    ) {
    }

    public function createFromExchangeRateADTO(ExchangeRateADTO $exchangeRateDTO): Currency
    {
        return $this->builder
            ->setName($exchangeRateDTO->currency)
            ->setCurrencyCode($exchangeRateDTO->code)
            ->setExchangeRate($exchangeRateDTO->mid)
            ->build();
    }
}
