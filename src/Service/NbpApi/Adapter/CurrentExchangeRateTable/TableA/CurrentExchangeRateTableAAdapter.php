<?php

declare(strict_types=1);

namespace App\Service\NbpApi\Adapter\CurrentExchangeRateTable\TableA;

use App\Exception\ValidationException;
use App\Service\NbpApi\Client\CurrentExchangeRateTable\CurrentExchangeRateTableClientInterface;
use App\Service\NbpApi\DTO\ExchangeRateTable\ExchangeRateTableADTO;
use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final class CurrentExchangeRateTableAAdapter implements CurrentExchangeRateTableAAdapterInterface
{
    public function __construct(
        protected CurrentExchangeRateTableClientInterface $tableAClient,
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator,
    ) {
    }

    public function getTableDTOs(): array
    {
        $response = $this->tableAClient->getResponse();

        $DTOs = $this->serializer->deserialize($response->getContent(), ExchangeRateTableADTO::class . '[]', 'json');
        $errors = $this->validator->validate($DTOs, groups: [NbpApiTableTypeEnum::A->value]);

        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        return $DTOs;
    }
}
