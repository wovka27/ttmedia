<?php
/** @var array $arResult */

/** @var array $arParams */

global $APPLICATION;

?>
<div class="filter-container">
    <?php
    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.filter',
        '',
        [
            'FILTER_ID'          => $arParams['FILTER_ID'],
            'GRID_ID'            => $arParams['GRID_ID'],
            'FILTER'             => $arParams['FILTERS'],
            'ENABLE_LIVE_SEARCH' => false,
            'ENABLE_LABEL'       => true,
            'THEME'              => 'BORDER',
        ],
    );
    ?>
</div>
