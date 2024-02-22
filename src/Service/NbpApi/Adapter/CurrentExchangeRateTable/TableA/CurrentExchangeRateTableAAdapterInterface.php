<?php

namespace App\Service\NbpApi\Adapter\CurrentExchangeRateTable\TableA;

use App\Service\NbpApi\DTO\ExchangeRateTable\ExchangeRateTableADTO;

interface CurrentExchangeRateTableAAdapterInterface
{
    /** @return ExchangeRateTableADTO[] */
    public function getTableDTOs(): array;
}
