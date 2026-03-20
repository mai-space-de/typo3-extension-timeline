<?php

defined('TYPO3') or die();

(function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_maitimeline_domain_model_timelineentry',
        'EXT:mai_timeline/Resources/Private/Language/locallang_csh_tx_maitimeline_domain_model_timelineentry.xlf'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_maitimeline_domain_model_timelineentry'
    );
})();
