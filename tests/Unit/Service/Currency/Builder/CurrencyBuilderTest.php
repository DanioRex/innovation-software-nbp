<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Currency\Builder;

use App\Entity\Currency\Currency;
use App\Exception\ValidationException;
use App\Service\Currency\Builder\CurrencyBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CurrencyBuilderTest extends TestCase
{
    private MockObject|ValidatorInterface $validator;

    public static function currencyDataProvider(): array
    {
        return [
            ['euro', CurrencyAlpha3::Euro, 4.5],
            ['dolar amerykański', CurrencyAlpha3::US_Dollar, 4.3],
            ['funt brytyjski', CurrencyAlpha3::Pound_Sterling, 5],
        ];
    }

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testConstruct(): void
    {
        $this->validator
            ->expects(self::never())
            ->method('validate');

        $currencyBuilder = new CurrencyBuilder($this->validator);

        $this->assertInstanceOf(CurrencyBuilder::class, $currencyBuilder);
    }

    public function testFailedBuild(): void
    {
        $violation = $this->createMock(ConstraintViolationInterface::class);
        $violation->method('getMessage')->willReturn('Error message');

        $violationList = new ConstraintViolationList([$violation]);

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with(
                $this->isInstanceOf(Currency::class),
            )
            ->willReturn($violationList);

        $currencyBuilder = new CurrencyBuilder($this->validator);

        $this->expectException(ValidationException::class);
        $currencyBuilder->build();
    }

    /** @dataProvider currencyDataProvider */
    public function testSuccessBuild(
        string $name,
        CurrencyAlpha3 $currencyCode,
        float $exchangeRate,
    ): void {
        $violationList = new ConstraintViolationList([]);

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with(
                $this->isInstanceOf(Currency::class),
            )
            ->willReturn($violationList);

        $currencyBuilder = new CurrencyBuilder($this->validator);
        $currency = $currencyBuilder
            ->setName($name)
            ->setCurrencyCode($currencyCode)
            ->setExchangeRate($exchangeRate)
            ->build();

        $this->assertInstanceOf(Currency::class, $currency);

        $this->assertEquals($name, $currency->getName());
        $this->assertEquals($currencyCode, $currency->getCurrencyCode());
        $this->assertEquals($exchangeRate, $currency->getExchangeRate());
    }
}
