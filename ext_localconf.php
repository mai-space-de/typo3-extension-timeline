<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiTimeline\Controller\TimelineController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'MaiTimeline',
    'TimelineList',
    [
        TimelineController::class => 'list',
    ],
    [],
);
