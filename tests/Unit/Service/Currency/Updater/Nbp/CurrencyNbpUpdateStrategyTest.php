<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Currency\Updater\Nbp;

use App\Service\Currency\Updater\Nbp\CurrencyNbpUpdateStrategy;
use App\Service\Currency\Updater\Nbp\Updater\CurrencyNbpUpdaterInterface;
use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CurrencyNbpUpdateStrategyTest extends TestCase
{
    private MockObject|CurrencyNbpUpdaterInterface $updaterA;

    protected function setUp(): void
    {
        $this->updaterA = $this->createMock(CurrencyNbpUpdaterInterface::class);
    }

    public function testConstruct(): void
    {
        $strategy = new CurrencyNbpUpdateStrategy(
            updaterA: $this->updaterA,
        );

        $this->assertInstanceOf(CurrencyNbpUpdateStrategy::class, $strategy);
    }

    public function testUpdateAllCurrenciesTableA(): void
    {
        $this->updaterA
            ->expects(self::once())
            ->method('updateAllCurrencies');

        $strategy = new CurrencyNbpUpdateStrategy(
            updaterA: $this->updaterA,
        );

        $strategy->updateAllCurrencies(NbpApiTableTypeEnum::A);
    }
}
