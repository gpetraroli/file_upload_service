<?php

namespace App\Command;

use App\service\TemporaryFileManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cron:deleteOldFiles',
    description: 'Add a short description for your command',
)]
class CronDeleteOldFilesCommand extends Command
{
    public function __construct(
        private TemporaryFileManager $temporaryFileManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->temporaryFileManager->deleteOldFiles();

        return Command::SUCCESS;
    }
}
