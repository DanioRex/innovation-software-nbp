<?php

declare(strict_types=1);

namespace App\Command\Currency;

use App\Service\Currency\Updater\Nbp\CurrencyNbpUpdateStrategyInterface;
use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:currency:update:nbp')]
final class UpdateCurrenciesNbpCommand extends Command
{
    public function __construct(
        protected CurrencyNbpUpdateStrategyInterface $currencyNbpUpdateStrategy,
        protected EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription('Update all existing currencies and create received from NBP API')
            ->addArgument('tableType', InputArgument::REQUIRED, 'NbpApiTableTypeEnum value');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $tableType = NbpApiTableTypeEnum::from($input->getArgument('tableType'));
            $this->currencyNbpUpdateStrategy->updateAllCurrencies($tableType);
            $this->entityManager->flush();
        } catch (\ValueError $e) {
            $output->writeln($e->getMessage());

            return Command::INVALID;
        } catch (\Throwable $t) {
            $output->writeln($t->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
