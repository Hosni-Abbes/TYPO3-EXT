<?php
return [
    'dependencies' => [
        'backend',
        'core',
        '@typo3/core/java-script-item-handler.js',
    ],
    'tags' => [
        'backend.form',
        'backend.module',
    ],
    'imports' => [
        '@hosni/hosni-site/import.js' => 'EXT:hosni_site/Resources/Public/JavaScript/import.js',
    ],
];
