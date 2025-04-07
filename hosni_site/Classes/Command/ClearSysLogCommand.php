<?php
namespace Hosni\HosniSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;


class ClearSysLogCommand extends Command
{
    private const TABLE = 'sys_log';
    
    protected function configure()
    {
        $this->setDescription('Keep only logs from last week.');
    }

    /**
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $mondayLastWeek = (new \DateTime())->modify('Monday last week')->getTimestamp();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        $queryBuilder
            ->delete(self::TABLE)
            ->where(
                $queryBuilder->expr()->lt('tstamp', $queryBuilder->createNamedParameter($mondayLastWeek, Connection::PARAM_STR))
            )
            ->executeQuery();

        return Command::SUCCESS;
    }
}