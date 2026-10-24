<?php

declare(strict_types=1);

use Maispace\MaiBase\Icon\StreamlineSvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$allIcons = 'EXT:mai_base/Resources/Public/Icons/all-icons/';

return [
    'ext-maispace-mai_timeline' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mai_timeline/Resources/Public/Icons/Extension.svg',
    ],
    'mai-timeline' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $allIcons . 'interface-hierarchy-2--node-organization-links-structure-link-nodes-network-hierarchy--Streamline-Core.svg',
    ],
];
