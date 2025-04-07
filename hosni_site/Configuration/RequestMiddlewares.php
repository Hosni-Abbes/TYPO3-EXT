<?php
return [
    'frontend' => [
        'hosni-account-redirectse' => [
            'target' => \Hosni\HosniSite\Middleware\RedirectMiddleware::class,
            'before' => [
                'typo3/cms-frontend/tsfe'
            ],
        ],
    ],
];
