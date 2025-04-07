<?php

return [
    'hosnisite-import' => [
        'labels' => [
            'title' => 'Import',
            'description' => 'Import products from xml.'
        ],
        'access' => 'user,group',
        'workspaces' => 'live',
        'position' => ['after' => 'web'],
        'appearance' => [
            'renderInModuleMenu' => true
        ],
        'iconIdentifier' => 'module-hosnisite-icon',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'extensionName' => 'HosniSite',
        'controllerActions' => [
            \Hosni\HosniSite\Controller\ImportController::class => [
                'list', 'import',
            ]
        ]
    ]
];