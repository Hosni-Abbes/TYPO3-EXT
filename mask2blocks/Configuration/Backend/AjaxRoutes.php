<?php

use T3dev\Mask2blocks\Controller\MigrationController;

return [
    'mask2blocks_prepare' => [
        'path' => '/module/tools/mask2blocks/prepare',
        'target' => MigrationController::class . '::prepareAction',
    ],
];
