<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\ContentElement\CType;
use Maispace\MaiBase\TableConfigurationArray\Helper;

$lang = Helper::localLangHelperFactory('mai_timeline', 'Default/locallang_db.xlf');

// Timeline content element (moved from mai_theme)
(new CType('maispace_timeline', $lang('ctype.timeline'), 'content-list-bullet'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('tx_maitimeline_items')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_widgets')
    ->register();

// Timeline items inline field
$GLOBALS['TCA']['tt_content']['columns']['tx_maitimeline_items'] = [
    'label' => $lang('field.items'),
    'config' => [
        'type' => 'inline',
        'foreign_table' => 'tx_maitimeline_item',
        'foreign_field' => 'parent_uid',
        'foreign_sortby' => 'sort',
        'appearance' => [
            'collapseAll' => true,
            'expandSingle' => true,
            'newRecordLinkAddTitle' => true,
            'showSynchronizationLink' => true,
            'showAllLocalizationLink' => true,
            'showPossibleLocalizationRecords' => true,
        ],
    ],
];

// Legacy plugin support (kept for backward compatibility)
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addPlugin([
    'label' => 'LLL:EXT:mai_timeline/Resources/Private/Language/Default/locallang_db.xlf:tt_content.CType.mai_timeline_list',
    'value' => 'mai_timeline_list',
    'icon' => 'mai-content',
    'group' => 'default',
]);

$GLOBALS['TCA']['tt_content']['types']['mai_timeline_list'] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header,
            pi_flexform,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
    ',
    'columnsOverrides' => [
        'pi_flexform' => [
            'label' => 'LLL:EXT:mai_timeline/Resources/Private/Language/Default/locallang_db.xlf:tt_content.pi_flexform.mai_timeline_list',
            'config' => [
                'ds' => [
                    'default' => 'FILE:EXT:mai_timeline/Configuration/FlexForms/TimelinePlugin.xml',
                ],
            ],
        ],
    ],
];
