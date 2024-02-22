<?php

declare(strict_types=1);

namespace App\Service\NbpApi\DTO\ExchangeRateTable;

use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract readonly class AbstractExchangeRateTableDTO
{
    public function __construct(
        #[Assert\EqualTo(value: NbpApiTableTypeEnum::A, groups: [NbpApiTableTypeEnum::A->value])]
        public NbpApiTableTypeEnum $table,

        public string $no,
        public DateTimeInterface $effectiveDate,
    ) {
    }
}
