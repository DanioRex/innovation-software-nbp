<?php

declare(strict_types=1);

namespace App\Repository\Currency;

use App\Entity\Currency\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;

class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function findOneByCurrencyCode(CurrencyAlpha3 $currencyCode): ?Currency
    {
        return $this->findOneBy([
            'currencyCode' => $currencyCode,
        ]);
    }
}
