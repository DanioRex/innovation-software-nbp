<?php

declare(strict_types=1);

namespace App\Service\NbpApi\DTO\ExchangeRateTable;

use App\Service\NbpApi\DTO\ExchangeRate\ExchangeRateADTO;
use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ExchangeRateTableADTO extends AbstractExchangeRateTableDTO
{
    public function __construct(
        #[Assert\All(
            constraints: [
                new Assert\Type(ExchangeRateADTO::class),
            ],
            groups: [NbpApiTableTypeEnum::A->value],
        )]
        #[Assert\Valid]
        /** @var ExchangeRateADTO[] $rates */
        public array $rates,

        NbpApiTableTypeEnum $table,
        string $no,
        \DateTimeInterface $effectiveDate,
    ) {
        parent::__construct(
            table: $table,
            no: $no,
            effectiveDate: $effectiveDate,
        );
    }
}
