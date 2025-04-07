<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();


ExtensionManagementUtility::addPlugin([
        'label' => 'HP Slider',
        'description' => 'Add a slider to home page',
        'group' => 'Home',
        'value' => 'hpslider',
        'icon' => 'content-hpslider',
    ],
    'CType',
    'hosni_site_hpslider',
);


// Register Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'hosni_site',
    'Pi1',
    'Title plugin',
    null,
    'Custom Plugins',
    'This is test plugin'
);
// Register Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'hosni_site',
    'Pi2',
    'API',
    null,
    'Custom Plugins',
    'This is Api plugin'
);

// Include FlexForm To Plugin
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['hosnisite_pi1'] = 'recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['hosnisite_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'hosnisite_pi1',    
    'FILE:EXT:hosni_site/Configuration/FlexForms/product_flexform.xml'
); 



