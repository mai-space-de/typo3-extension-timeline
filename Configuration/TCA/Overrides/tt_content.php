<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiTimeline',
    'MaiTimeline',
    'LLL:EXT:mai_timeline/Resources/Private/Language/locallang_db.xlf:plugin.timeline.title',
    'EXT:mai_timeline/Resources/Public/Icons/tx_maitimeline_domain_model_timelineentry.svg',
    'plugins',
    '',
    'FILE:EXT:mai_timeline/Configuration/FlexForms/flexform_timeline.xml'
);
