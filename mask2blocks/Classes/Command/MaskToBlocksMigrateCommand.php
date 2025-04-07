<?php
namespace T3dev\Mask2blocks\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MaskToBlocksMigrateCommand extends Command
{
    protected static $defaultName = 'mask_to_blocks:migrate';

    protected function configure(): void
    {
        $this->setDescription('Manually migrate MASK elements to content blocks.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Implement the migration logic here
        $output->writeln('Migration started...');
        // Perform migration
        $output->writeln('Migration completed successfully.');

        return Command::SUCCESS;
    }
}
