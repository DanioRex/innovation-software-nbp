<?php

declare(strict_types=1);

namespace App\Service\Currency\Builder;

use App\Entity\Currency\Currency;
use App\Exception\ValidationException;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CurrencyBuilder implements CurrencyBuilderInterface
{
    private Currency $currency;

    public function __construct(
        protected ValidatorInterface $validator,
    ) {
        $this->clearCurrency();
    }

    public function setName(string $name): self
    {
        $this->currency->setName($name);

        return $this;
    }

    public function setCurrencyCode(CurrencyAlpha3 $currencyCode): self
    {
        $this->currency->setCurrencyCode($currencyCode);

        return $this;
    }

    public function setExchangeRate(float $exchangeRate): self
    {
        $this->currency->setExchangeRate($exchangeRate);

        return $this;
    }

    public function build(): Currency
    {
        $errors = $this->validator->validate($this->currency);
        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        $currency = $this->currency;

        $this->clearCurrency();

        return $currency;
    }

    private function clearCurrency(): void
    {
        $this->currency = new Currency();
    }
}
