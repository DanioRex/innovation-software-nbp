<?php

declare(strict_types=1);

namespace App\DataFixtures\Currency;

use App\Service\Currency\Builder\CurrencyBuilderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;

class CurrencyFixtures extends Fixture
{
    public function __construct(
        protected CurrencyBuilderInterface $currencyBuilder,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create();

        $availableCurrencyCodes = CurrencyAlpha3::cases();
        for ($i = 0; $i < 5; $i++) {
            /** @var CurrencyAlpha3 $currencyCode */
            $currencyCode = $faker->randomElement($availableCurrencyCodes);

            $currency = $this->currencyBuilder
                ->setCurrencyCode($currencyCode)
                ->setName(
                    str_replace('_', ' ', $currencyCode->name),
                )
                ->setExchangeRate(
                    $faker->randomFloat(
                        nbMaxDecimals: 5,
                        min: 0.5,
                        max: 10,
                    ),
                )
                ->build();

            $key = array_search($currencyCode, $availableCurrencyCodes);
            if ($key !== false) {
                unset($availableCurrencyCodes[$key]);
            }

            $manager->persist($currency);
        }

        $manager->flush();
    }
}
