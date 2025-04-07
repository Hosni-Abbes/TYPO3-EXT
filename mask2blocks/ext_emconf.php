<?php
$EM_CONF['mask2blocks'] = [
    'title' => 'Mask to Content Blocks',
    'description' => 'Migrates mask elements to content blocks.',
    'category' => 'plugin',
    'author' => 'Hosni Abbes',
    'author_email' => 'hosny.abbes@gmail.com',
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.99-13.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
