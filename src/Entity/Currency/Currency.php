<?php

declare(strict_types=1);

namespace App\Entity\Currency;

use App\Entity\AbstractBaseEntity;
use App\Repository\Currency\CurrencyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency extends AbstractBaseEntity
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(name: 'name', type: Types::STRING, length: 255)]
    protected string $name;

    #[Assert\NotBlank]
    #[ORM\Column(name: 'currency_code', length: 3, unique: true, enumType: CurrencyAlpha3::class)]
    protected CurrencyAlpha3 $currencyCode;

    #[Assert\NotBlank]
    #[Assert\GreaterThan(value: 0)]
    #[ORM\Column(name: 'exchange_rate', type: Types::FLOAT)]
    protected float $exchangeRate;

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCurrencyCode(): CurrencyAlpha3
    {
        return $this->currencyCode;
    }

    public function setExchangeRate(float $exchangeRate): void
    {
        $this->exchangeRate = $exchangeRate;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCurrencyCode(CurrencyAlpha3 $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }
}
