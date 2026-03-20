<?php

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'LLL:EXT:timeline/Resources/Private/Language/locallang_db.xlf:tx_timeline_domain_model_timelineentry',
        'label' => 'title',
        'label_alt' => 'year',
        'label_alt_force' => true,
        'default_sortby' => 'year DESC',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'year,title,description',
        'iconfile' => 'EXT:timeline/Resources/Public/Icons/tx_timeline_domain_model_timelineentry.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    year, title, description, media, categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden, starttime, endtime
            ',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'year' => [
            'exclude' => false,
            'label' => 'LLL:EXT:timeline/Resources/Private/Language/locallang_db.xlf:tx_timeline_domain_model_timelineentry.year',
            'config' => [
                'type' => 'number',
                'size' => 6,
                'required' => true,
                'range' => [
                    'lower' => 1,
                    'upper' => 9999,
                ],
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => 'LLL:EXT:timeline/Resources/Private/Language/locallang_db.xlf:tx_timeline_domain_model_timelineentry.title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'exclude' => false,
            'label' => 'LLL:EXT:timeline/Resources/Private/Language/locallang_db.xlf:tx_timeline_domain_model_timelineentry.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 10,
                'cols' => 50,
            ],
        ],
        'media' => [
            'exclude' => false,
            'label' => 'LLL:EXT:timeline/Resources/Private/Language/locallang_db.xlf:tx_timeline_domain_model_timelineentry.media',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'media',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '--linebreak--,
                                    --palette--;;filePalette',
                            ],
                        ],
                    ],
                    'minitems' => 0,
                    'maxitems' => 10,
                ],
                'jpg,jpeg,png,gif,webp,svg'
            ),
        ],
        'categories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:timeline/Resources/Private/Language/locallang_db.xlf:tx_timeline_domain_model_timelineentry.categories',
            'config' => [
                'type' => 'category',
            ],
        ],
    ],
];
