<?php
defined('TYPO3') or die();

$configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Model\Dto\EmConfiguration::class);

return [
    'ctrl' => [
        'title' => 'Products',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'default_sortby' => 'title',
        'delete' => 'deleted',
        'hideTable' => false,
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime'
        ],
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'searchFields' => 'title',
        'iconfile' => 'EXT:hosni_site/Resources/Public/Icons/typo3.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'uid,pid,sys_language_uid,l10n_parent,l10n_diffsource,hidden,deleted,starttime,endtime,title,desc,
        code,head_pole,logo,service'
    ],
    'columns' => [
        'title' => [
            'exclude' => false,
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ]
        ],
        'desc' => [
            'exclude' => false,
            'label' => 'Desc',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'title,desc,
            --div--;Infos,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;paletteLanguage,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;paletteHidden,
                --palette--;;paletteAccess,
            '
        ]
    ],
];
