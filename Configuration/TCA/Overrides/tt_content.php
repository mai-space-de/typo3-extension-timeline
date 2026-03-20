<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Timeline',
    'Timeline',
    'LLL:EXT:timeline/Resources/Private/Language/locallang_db.xlf:plugin.timeline.title',
    'EXT:timeline/Resources/Public/Icons/tx_timeline_domain_model_timelineentry.svg',
    'plugins'
);

$pluginSignature = 'timeline_timeline';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:timeline/Configuration/FlexForms/flexform_timeline.xml'
);
