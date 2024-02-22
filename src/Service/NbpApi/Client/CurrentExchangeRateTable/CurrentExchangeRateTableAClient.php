<?php

declare(strict_types=1);

namespace App\Service\NbpApi\Client\CurrentExchangeRateTable;

use App\Service\NbpApi\Client\AbstractNbpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CurrentExchangeRateTableAClient extends AbstractNbpClient implements CurrentExchangeRateTableClientInterface
{
    public function getResponse(): ResponseInterface
    {
        return $this->nbpApiClient->request('GET', '/api/exchangerates/tables/A');
    }
}
