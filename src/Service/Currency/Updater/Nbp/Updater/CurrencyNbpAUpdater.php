<?php

declare(strict_types=1);

namespace App\Service\Currency\Updater\Nbp\Updater;

use App\Repository\Currency\CurrencyRepository;
use App\Service\Currency\Factory\Nbp\CurrencyNbpFactoryInterface;
use App\Service\NbpApi\Adapter\CurrentExchangeRateTable\TableA\CurrentExchangeRateTableAAdapterInterface;
use App\Service\NbpApi\DTO\ExchangeRateTable\ExchangeRateTableADTO;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyNbpAUpdater implements CurrencyNbpUpdaterInterface
{
    public function __construct(
        protected CurrentExchangeRateTableAAdapterInterface $exchangeRateTableAAdapter,
        protected CurrencyNbpFactoryInterface $currencyFactory,
        protected CurrencyRepository $currencyRepository,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function updateAllCurrencies(): void
    {
        $DTOs = $this->exchangeRateTableAAdapter->getTableDTOs();

        foreach ($DTOs as $exchangeRateTableDTO) {
            $this->processTableDTO($exchangeRateTableDTO);
        }
    }

    protected function processTableDTO(ExchangeRateTableADTO $exchangeRateTableDTO): void
    {
        foreach ($exchangeRateTableDTO->rates as $rateDTO) {
            $currency = $this->currencyRepository->findOneByCurrencyCode($rateDTO->code);

            if (null === $currency) {
                $currency = $this->currencyFactory->createFromExchangeRateADTO($rateDTO);
                $this->entityManager->persist($currency);
            }

            $currency->setExchangeRate($rateDTO->mid);
        }
    }
}
