<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_timeline', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_maitimeline_entry')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setAlternativeLabelFields('date')
    ->setSearchFields('title, content')
    ->setIconFile('EXT:mai_timeline/Resources/Public/Icons/tx_maitimeline_entry.svg')
    ->setDefaultSorting('ORDER BY date DESC')
    ->setThumbnailField('image')
    ->addColumn(
        'title',
        $lang('tx_maitimeline_entry.title'),
        ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim,required']
    )
    ->addColumn(
        'content',
        $lang('tx_maitimeline_entry.content'),
        [
            'type' => 'text',
            'rows' => 15,
            'cols' => 50,
            'enableRichtext' => true,
            'richtextConfiguration' => 'default',
        ]
    )
    ->addColumn(
        'date',
        $lang('tx_maitimeline_entry.date'),
        ['type' => 'datetime', 'format' => 'date', 'eval' => 'required']
    )
    ->addColumn(
        'year',
        $lang('tx_maitimeline_entry.year'),
        [
            'type' => 'number',
            'format' => 'integer',
            'range' => ['lower' => 1900, 'upper' => 2100],
        ]
    )
    ->addColumn(
        'image',
        $lang('tx_maitimeline_entry.image'),
        [
            'type' => 'file',
            'allowed' => 'common-image-types',
            'maxitems' => 1,
            'appearance' => [
                'createNewRelationLinkTitle' => $lang('tx_maitimeline_entry.image.addFile'),
            ],
        ]
    )
    ->addColumn(
        'categories',
        $lang('tx_maitimeline_entry.categories'),
        ['type' => 'category']
    )
    ->addTypeShowItem(
        '0',
        'title, date, year, content, image, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
