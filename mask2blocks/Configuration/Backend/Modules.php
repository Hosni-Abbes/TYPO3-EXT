<?php
use T3dev\Mask2blocks\Controller\MigrationController;

// @todo: for security reasons only add the module for backend application context and if user is admin

return [
    'tools_mask2blocks' => [
        'parent' => 'tools',
        'position' => 'bottom',
        'access' => 'admin', // only admin have access for this module
        'path' => '/module/tools/mask2blocks',
        'iconIdentifier' => 'module-mask2blocks',
        'labels' => 'LLL:EXT:mask2blocks/Resources/Private/Language/locallang.xlf',
        'extensionName' => 'Mask2blocks',
        'target' => [
            '_default' => MigrationController::class . '::indexAction',
        ],
        'controllerActions' => [
            MigrationController::class => [
                'index', 'prepare', 'overview', 'migrate'
            ],
        ],
    ]
];
