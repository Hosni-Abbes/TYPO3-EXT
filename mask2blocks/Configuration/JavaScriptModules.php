<?php
return [
    'dependencies' => [
        'backend',
        'core'
    ],
    'tags' => [
        'backend.form',
        'backend.module'
    ],
    'imports' => [
        '@t3dev/mask2blocks/migration.js' => 'EXT:mask2blocks/Resources/Public/JavaScript/migration.js'
    ]
];