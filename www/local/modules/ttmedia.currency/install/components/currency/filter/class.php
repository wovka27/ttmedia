<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Filter\DateType;
use Bitrix\Main\UI\Filter\NumberType;

class TtmediaCurrencyFilterComponent extends CBitrixComponent
{
    public function executeComponent(): void
    {
        $this->includeComponentTemplate();
    }
}
