<?php

declare(strict_types=1);

namespace App\Service\NbpApi\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractNbpClient
{
    public function __construct(
        protected HttpClientInterface $nbpApiClient,
    ) {
    }
}
