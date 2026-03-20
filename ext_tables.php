<?php

defined('TYPO3') or die();

(function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_timeline_domain_model_timelineentry',
        'EXT:timeline/Resources/Private/Language/locallang_csh_tx_timeline_domain_model_timelineentry.xlf'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_timeline_domain_model_timelineentry'
    );
})();
