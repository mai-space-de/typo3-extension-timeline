<?php

declare(strict_types=1);

defined('TYPO3') or die();

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
