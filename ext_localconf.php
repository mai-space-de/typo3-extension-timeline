<?php

defined('TYPO3') or die();

(function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'MaiTimeline',
        'MaiTimeline',
        [
            \Maispace\MaiTimeline\Controller\TimelineController::class => 'index,compact,show',
        ],
        [
            \Maispace\MaiTimeline\Controller\TimelineController::class => '',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
})();
