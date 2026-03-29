<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Mai Timeline',
    'description' => 'Timeline extension with vertical, compact, and detail views for displaying chronological content. Categories use TYPO3 `sys_category`, sharing the same tree as `mai_news`, `mai_gallery`, and `mai_faq`.',
    'category' => 'module',
    'author' => 'Maispace',
    'author_email' => '',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
