<?php

defined('TYPO3') or die;

$additionalFields = [
    "is_focus" => [
        'exclude' => true,
        'label' => "Focus news",
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'default' => 0,
        ],
    ],
    'slug_test' => [
        'exclude' => true,
        'label' => 'Test Slug',
        'config' => [
            'type' => 'slug',
            'generatorOptions' => [
                'fields' => ['title'],
                'replacements' => [
                    '(f/m)' => '',
                    '-' => '-'
                ],
            ],
            'fallbackCharacter' => '-',
            'prependSlash' => true,
            'eval' => 'uniqueInPid',
        ],
    ],
    'number' => [
        'exclude' => true,
        'label' => 'Number',
        'config' => [
            'type' => 'number',
            'format' => 'integer',
            'required' => true,
        ]
    ],
    'folder' => [
        'exclude' => true,
        'label' => 'Folder',
        'config' => [
            'type' => 'folder',
            'default' => 'fileadmin/',
        ]
    ],
    'password' => [
        'exclude' => true,
        'label' => 'Password',
        'config' => [
            'type' => 'password',
        ]
    ],
    'link' => [
        'exclude' => true,
        'label' => 'Link',
        'config' => [
            'type' => 'link',
            'required' => true,
            'allowedTypes' => ['url','page'],
            'size' => 30,
        ]
    ],
    'date' => [
        'exclude' => true,
        'label' => 'Date',
        'config' => [
            'type' => 'datetime',
            'format' => 'date',
            'size' => 30,
            'requied' => true,
            'default' => 0
        ]
    ],
    'color' => [
        'exclude' => true,
        'label' => 'Color',
        'config' => [
            'type' => 'color',
            'size' => 30,
            'valuePicker' => [
                'items' => [
                    ['Red', '#961515'],
                    ['Yellow', '#7a8722']
                ]
            ]
        ]
    ],
    'image' => [
        'exclude' => true,
        'label' => 'Image',
        'config' => [
            'type' => 'file',
            'maxitems' => 3,
            'minitems' => 1,
            'allowed' => ['jpg', 'png']
        ]
    ],
    'uuid' => [
        'label' => 'My record identifier',
        'config' => [
            'type' => 'uuid',
            'version' => 6,
        ],
    ],

];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $additionalFields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news','uuid,image,color,date,link,password,folder,is_focus,slug_test,number','','after:type');
