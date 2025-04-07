<?php

defined('TYPO3') or die();

call_user_func(function () {

    

    // $GLOBALS['TYPO3_CONF_VARS']['BE']['defaultPageTSconfig'] = '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:hosni_site/Configuration/TSConfig/">';
    // Extend newsController
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\GeorgRinger\News\Controller\NewsController::class] = [
        'className' => \Hosni\HosniSite\Controller\NewsController::class
    ];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\GeorgRinger\News\Domain\Repository\NewsRepository::class] = [
        'className' => \Hosni\HosniSite\Domain\Repository\NewsRepository::class
    ];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\GeorgRinger\News\Domain\Model\News::class] = [
        'className' => \Hosni\HosniSite\Domain\Model\News::class
    ];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\GeorgRinger\News\Domain\Model\Dto\NewsDemand::class] = [
        'className' => \Hosni\HosniSite\Domain\Model\Dto\NewsDemand::class
    ];

    // $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['news']['extender'][\GeorgRinger\News\Domain\Model\News::class][1594117551] =
    //     'EXT:hosni_site/Classes/Domain/Model/News.php';

    // $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['news']['extender'][\GeorgRinger\News\Domain\Model\Dto\NewsDemand::class][1594117552] =
    //     'EXT:hosni_site/Classes/Domain/Model/Dto/NewsDemand.php';


    // $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['news']['extender'][\GeorgRinger\News\Domain\Repository\NewsRepository::class][1594117553] =
    //     'EXT:hosni_site/Classes/Domain/Repository/NewsRepository.php';

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'hosni_site',
        'Pi1',
        [
            \Hosni\HosniSite\Controller\ProductController::class => 'list,create,update,form,delete',
            \Hosni\HosniSite\Controller\ApiController::class => 'list'
        ],
        [
            \Hosni\HosniSite\Controller\ProductController::class => 'list,create,update,form,delete'
        ]
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'hosni_site',
        'Pi2',
        [
            \Hosni\HosniSite\Controller\ApiController::class => 'list'
        ],
        [
            \Hosni\HosniSite\Controller\ApiController::class => 'list'
        ]
    );


});
