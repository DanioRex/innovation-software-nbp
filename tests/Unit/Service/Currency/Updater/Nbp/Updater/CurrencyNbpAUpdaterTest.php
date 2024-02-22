<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Currency\Updater\Nbp\Updater;

use App\Entity\Currency\Currency;
use App\Repository\Currency\CurrencyRepository;
use App\Service\Currency\Factory\Nbp\CurrencyNbpFactoryInterface;
use App\Service\Currency\Updater\Nbp\Updater\CurrencyNbpAUpdater;
use App\Service\NbpApi\Adapter\CurrentExchangeRateTable\TableA\CurrentExchangeRateTableAAdapterInterface;
use App\Service\NbpApi\DTO\ExchangeRate\ExchangeRateADTO;
use App\Service\NbpApi\DTO\ExchangeRateTable\ExchangeRateTableADTO;
use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;

final class CurrencyNbpAUpdaterTest extends TestCase
{
    protected MockObject|CurrentExchangeRateTableAAdapterInterface $exchangeRateTableAAdapter;
    protected MockObject|CurrencyNbpFactoryInterface $currencyFactory;
    protected MockObject|CurrencyRepository $currencyRepository;
    protected MockObject|EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->exchangeRateTableAAdapter = $this->createMock(CurrentExchangeRateTableAAdapterInterface::class);
        $this->currencyFactory = $this->createMock(CurrencyNbpFactoryInterface::class);
        $this->currencyRepository = $this->createMock(CurrencyRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    public function testConstruct(): void
    {
        $updater = new CurrencyNbpAUpdater(
            exchangeRateTableAAdapter: $this->exchangeRateTableAAdapter,
            currencyFactory: $this->currencyFactory,
            currencyRepository: $this->currencyRepository,
            entityManager: $this->entityManager,
        );

        $this->assertInstanceOf(CurrencyNbpAUpdater::class, $updater);
    }

    public function testUpdateAllCurrenciesOnlyUpdate(): void
    {
        $currencyName = 'Euro';
        $currencyCode = CurrencyAlpha3::Euro;
        $exchangeRate = 4.6;

        $DTOs = [
            new ExchangeRateTableADTO(
                rates: [
                    new ExchangeRateADTO(
                        mid: $exchangeRate,
                        currency: $currencyName,
                        code: $currencyCode,
                    ),
                ],
                table: NbpApiTableTypeEnum::A,
                no: 'string',
                effectiveDate: new \DateTimeImmutable('2021-01-01'),
            ),
        ];

        $this->exchangeRateTableAAdapter
            ->expects(self::once())
            ->method('getTableDTOs')
            ->willReturn($DTOs);

        $currency = $this->createMock(Currency::class);

        $this->currencyRepository
            ->expects(self::once())
            ->method('findOneByCurrencyCode')
            ->with(
                $this->equalTo($currencyCode),
            )
            ->willReturn($currency);

        $this->currencyFactory
            ->expects(self::never())
            ->method('createFromExchangeRateADTO');

        $this->entityManager
            ->expects(self::never())
            ->method('persist');

        $currency
            ->expects(self::once())
            ->method('setExchangeRate')
            ->with($exchangeRate);

        $updater = new CurrencyNbpAUpdater(
            exchangeRateTableAAdapter: $this->exchangeRateTableAAdapter,
            currencyFactory: $this->currencyFactory,
            currencyRepository: $this->currencyRepository,
            entityManager: $this->entityManager,
        );

        $updater->updateAllCurrencies();
    }

    public function testUpdateAllCurrenciesOnlyCreate(): void
    {
        $currencyName = 'Euro';
        $currencyCode = CurrencyAlpha3::Euro;
        $exchangeRate = 4.6;

        $rateDTO = new ExchangeRateADTO(
            mid: $exchangeRate,
            currency: $currencyName,
            code: $currencyCode,
        );

        $DTOs = [
            new ExchangeRateTableADTO(
                rates: [$rateDTO],
                table: NbpApiTableTypeEnum::A,
                no: 'string',
                effectiveDate: new \DateTimeImmutable('2021-01-01'),
            ),
        ];

        $this->exchangeRateTableAAdapter
            ->expects(self::once())
            ->method('getTableDTOs')
            ->willReturn($DTOs);

        $this->currencyRepository
            ->expects(self::once())
            ->method('findOneByCurrencyCode')
            ->with(
                $this->equalTo($currencyCode),
            )
            ->willReturn(null);

        $currency = $this->createMock(Currency::class);

        $this->currencyFactory
            ->expects(self::once())
            ->method('createFromExchangeRateADTO')
            ->with(
                $this->equalTo($rateDTO),
            )
            ->willReturn($currency);

        $this->entityManager
            ->expects(self::once())
            ->method('persist')
            ->with($currency);

        $currency
            ->expects(self::once())
            ->method('setExchangeRate')
            ->with($exchangeRate);

        $updater = new CurrencyNbpAUpdater(
            exchangeRateTableAAdapter: $this->exchangeRateTableAAdapter,
            currencyFactory: $this->currencyFactory,
            currencyRepository: $this->currencyRepository,
            entityManager: $this->entityManager,
        );

        $updater->updateAllCurrencies();
    }
}
