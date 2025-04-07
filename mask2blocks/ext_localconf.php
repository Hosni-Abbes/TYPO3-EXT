<?php
defined('TYPO3') || die;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\Writer\FileWriter;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['Mask2blocks_rollUpgradeWizard']
    = \T3dev\Mask2blocks\Upgrades\MaskToBlocksUpgradeWizard::class;

// LOG ERRORS
$GLOBALS['TYPO3_CONF_VARS']['LOG']['T3dev']['Mask2blocks']['Controller']['writerConfiguration'] = [
    // Configuration for ERROR level log entries
    LogLevel::ERROR => [
        FileWriter::class => [
            'logFile' => Environment::getVarPath() . '/log/mask2blocks.log',
        ],
    ],
];