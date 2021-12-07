<?php

namespace App\Command;

use App\Service\Import\ExamImport;
use App\Service\Import\UserImport;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ImportCSVs',
    description: 'Imports CSVs',
)]
class ImportCSVsCommand extends Command
{
    public function __construct(private UserImport $userImport, private ExamImport $examImport)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('import', null, InputOption::VALUE_NONE, 'Imports CSV and saves it in the Database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('import')) {
            $countedImports = $this->userImport->import(__DIR__ . '/../../import/user.csv');

            $importedExams = $this->examImport->import(__DIR__ . '/../../import/exam.csv');

            $countedImports[0] += $importedExams[0];
            $countedImports[1] += $importedExams[1];

            $io->success('Imported ' . $countedImports[0] . ' of ' . $countedImports[1] . ' records successfully!');

            if($countedImports[0] !== $countedImports[1]) {
                $io->note('If not all records were imported, it could be because the record is already written to the database!');
            }
        }

        return Command::SUCCESS;
    }
}
