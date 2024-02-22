<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Currency\Factory\Nbp;

use App\Entity\Currency\Currency;
use App\Service\Currency\Builder\CurrencyBuilderInterface;
use App\Service\Currency\Factory\Nbp\CurrencyNbpFactory;
use App\Service\NbpApi\DTO\ExchangeRate\ExchangeRateADTO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;

final class CurrencyNbpFactoryTest extends TestCase
{
    private MockObject|CurrencyBuilderInterface $builder;

    public static function exchangeRateADTODataProvider(): array
    {
        return [
            [4.5, CurrencyAlpha3::Euro, 'euro'],
            [4.3, CurrencyAlpha3::US_Dollar, 'dolar amerykański'],
            [5, CurrencyAlpha3::Pound_Sterling, 'funt brytyjski'],
        ];
    }

    protected function setUp(): void
    {
        $this->builder = $this->createMock(CurrencyBuilderInterface::class);
    }

    public function testConstructor(): void
    {
        $currencyNbpFactory = new CurrencyNbpFactory($this->builder);

        $this->assertInstanceOf(CurrencyNbpFactory::class, $currencyNbpFactory);
    }

    /** @dataProvider exchangeRateADTODataProvider */
    public function testCreateFromExchangeRateDTO(
        float $mid,
        CurrencyAlpha3 $code,
        string $currency,
    ): void {
        $exchangeRateADTO = new ExchangeRateADTO(
            mid: $mid,
            currency: $currency,
            code: $code,
        );

        $this->builder
            ->expects(self::once())
            ->method('setName')
            ->with(
                $this->equalTo($currency),
            )
            ->willReturn($this->builder);
        $this->builder
            ->expects(self::once())
            ->method('setCurrencyCode')
            ->with(
                $this->equalTo($code),
            )
            ->willReturn($this->builder);
        $this->builder
            ->expects(self::once())
            ->method('setExchangeRate')
            ->with(
                $this->equalTo($mid),
            )
            ->willReturn($this->builder);

        $this->builder
            ->expects(self::once())
            ->method('build')
            ->willReturn(new Currency());

        $currencyNbpFactory = new CurrencyNbpFactory($this->builder);
        $currency = $currencyNbpFactory->createFromExchangeRateADTO($exchangeRateADTO);

        $this->assertInstanceOf(Currency::class, $currency);
    }
}
