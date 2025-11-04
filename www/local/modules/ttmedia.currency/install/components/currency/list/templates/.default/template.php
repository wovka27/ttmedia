<?php

global $APPLICATION;

/** @var array $arResult */
/** @var array $arParams */

if (!empty($arResult['FILTERS'])) {
    $APPLICATION->IncludeComponent(
        'currency:filter',
        '',
        [
            'FILTER_ID' => $arResult['FILTER_ID'],
            'GRID_ID'   => $arParams['GRID_ID'],
            'FILTERS'   => $arResult['FILTERS'],
        ],
    );
}

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID'            => $arParams['GRID_ID'],
        'COLUMNS'            => $arResult['COLUMNS'],
        'ROWS'               => $arResult['ROWS'],
        'TOTAL_ROWS_COUNT'   => $arResult['TOTAL_COUNT'],
        'NAV_OBJECT'         => $arResult['NAV_OBJECT'],
        'SORT'               => $arResult['SORT'],
        'SORT_VARS'          => $arResult['SORT_VARS'],
        'DEFAULT_PAGE_SIZES' => $arParams['PAGE_SIZE'],
        '~NAV_PARAMS'        => ['SHOW_ALWAYS' => false],
        'SHOW_PAGESIZE'      => true,
        'PAGE_SIZES'         => [
            [
                'NAME'  => 5,
                'VALUE' => 5,
            ],
            [
                'NAME'  => 10,
                'VALUE' => 10,
            ],
            [
                'NAME'  => 20,
                'VALUE' => 20,
            ],
        ],

        'SHOW_GRID_SETTINGS_MENU' => true,
        'SHOW_PAGINATION'         => true,
        'SHOW_TOTAL_COUNTER'      => true,
        'ALLOW_COLUMNS_SORT'      => true,
        'ALLOW_COLUMNS_RESIZE'    => false,
        'SHOW_ROW_CHECKBOXES'     => false,
        'SHOW_SELECTED_COUNTER'   => false,

        'AJAX_MODE'           => 'Y',
        'AJAX_OPTION_JUMP'    => 'N',
        'AJAX_OPTION_STYLE'   => 'N',
        'AJAX_OPTION_HISTORY' => 'N',

    ],
);