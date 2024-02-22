<?php

namespace App\Service\NbpApi\Client\CurrentExchangeRateTable;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface CurrentExchangeRateTableClientInterface
{
    public function getResponse(): ResponseInterface;
}
