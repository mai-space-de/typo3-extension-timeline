<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CategoryConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\DatetimeConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\NumberConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_timeline', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_maitimeline_entry')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setAlternativeLabelFields('date')
    ->setIconFile('EXT:mai_timeline/Resources/Public/Icons/tx_maitimeline_entry.svg')
    ->setDefaultSorting('ORDER BY date DESC')
    ->setThumbnailField('image')
    ->addColumn(
        'title',
        $lang('tx_maitimeline_entry.title'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')->setRequired()
    )
    ->addColumn(
        'content',
        $lang('tx_maitimeline_entry.content'),
        (new TextConfig())->setRows(15)->setCols(50)->enableRte()->setRichtextConfiguration('default')
    )
    ->addColumn(
        'date',
        $lang('tx_maitimeline_entry.date'),
        (new DatetimeConfig())->setFormat('date')->setRequired()
    )
    ->addColumn(
        'year',
        $lang('tx_maitimeline_entry.year'),
        (new NumberConfig())->setFormat('integer')->setRange(1900, 2100)
    )
    ->addColumn(
        'image',
        $lang('tx_maitimeline_entry.image'),
        (new FileConfig())
            ->setAllowed('common-image-types')
            ->setMaxItems(1)
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_maitimeline_entry.image.addFile'),
            ])
    )
    ->addColumn(
        'categories',
        $lang('tx_maitimeline_entry.categories'),
        new CategoryConfig()
    )
    ->addTypeShowItem(
        '0',
        'title, date, year, content, image, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
