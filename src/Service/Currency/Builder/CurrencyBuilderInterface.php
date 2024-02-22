<?php

namespace App\Service\Currency\Builder;

use App\Entity\Currency\Currency;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;

interface CurrencyBuilderInterface
{
    public function setName(string $name): self;
    public function setCurrencyCode(CurrencyAlpha3 $currencyCode): self;
    public function setExchangeRate(float $exchangeRate): self;
    public function build(): Currency;
}
