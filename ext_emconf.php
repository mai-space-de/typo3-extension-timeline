<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Timeline',
    'description' => 'A timeline extension for TYPO3 with vertical, compact, and detail views.',
    'category' => 'plugin',
    'author' => 'maispace',
    'author_email' => '',
    'author_company' => 'maispace',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.99.99',
            'php' => '8.1.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
