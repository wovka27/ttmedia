<?php

use Bitrix\Main\UI\Filter\DateType;
use Bitrix\Main\UI\Filter\NumberType;
use Bitrix\Main\UI\PageNavigation;
use Ttmedia\Currency\CourseTable;
use Bitrix\Main\UI\Filter\Options as FilterOptions;

class TtmediaCurrencyListComponent extends CBitrixComponent
{
    public function executeComponent(): void
    {
        $this->arResult['FILTER_ID'] = $this->arParams['GRID_ID'].'_filter';

        $this->arResult['FILTERS'] = $this->getFilter();

        $this->arResult['COLUMNS'] = $this->getGridColumns();

        $gridOptions = new \Bitrix\Main\Grid\Options($this->arParams['GRID_ID']);

        $nav = new PageNavigation($this->arParams['GRID_ID'].'_nav');
        $navParams = $gridOptions->getNavParams(['nPageSize' => $this->arParams['PER_PAGE']]);
        $pageSize = (int) $navParams['nPageSize'];
        $nav->allowAllRecords(false)->setPageSize($pageSize)->initFromUri();

        $queryObject = \Ttmedia\Currency\CourseTable::getList([
            'select'      => ['*'],
            'filter'      => $this->getDataFilter(),
            'offset'      => $nav->getOffset(),
            'limit'       => $nav->getLimit(),
            'count_total' => true,
            'order'       => $this->getGridOrder(),
        ]);

        $items = $queryObject->fetchAll();

        $this->arResult['ROWS'] = $this->getRows($items);

        $nav->setRecordCount($queryObject->getCount());
        $this->arResult['TOTAL_COUNT'] = $nav->getRecordCount();
        $this->arResult['NAV_OBJECT'] = $nav;

        if (!empty($this->arResult['FILTERS'])) {
            $gridOptions = new CGridOptions($this->arParams['GRID_ID']);
            $gridSorting = $gridOptions->GetSorting();
            $this->arResult['SORT'] = $gridSorting['sort'];
            $this->arResult['SORT_VARS'] = $gridSorting['vars'];
        }

        $this->includeComponentTemplate();
    }

    protected function getFilter(): array
    {
        $resultFilterColumns = [];

        $allFilterColumns = [
            'id'     => [
                'id'   => 'id',
                'name' => 'id',
                'type' => 'integer',
            ],
            'code'   => [
                'id'   => 'code',
                'name' => 'code',
                'type' => 'string',
            ],
            'course' => [
                'id'      => 'course',
                'name'    => 'course',
                'type'    => 'number',
                'exclude' => [
                    NumberType::LESS,
                    NumberType::MORE,
                ],
            ],
            'date'   => [
                'id'      => 'date',
                'name'    => 'Дата',
                'type'    => 'date',
                'default' => true,
                'exclude' => [
                    DateType::YESTERDAY,
                    DateType::CURRENT_DAY,
                    DateType::TOMORROW,
                    DateType::CURRENT_WEEK,
                    DateType::CURRENT_MONTH,
                    DateType::CURRENT_QUARTER,
                    DateType::LAST_7_DAYS,
                    DateType::LAST_30_DAYS,
                    DateType::LAST_60_DAYS,
                    DateType::LAST_90_DAYS,
                    DateType::PREV_DAYS,
                    DateType::NEXT_DAYS,
                    DateType::MONTH,
                    DateType::QUARTER,
                    DateType::YEAR,
                    DateType::EXACT,
                    DateType::LAST_WEEK,
                    DateType::LAST_MONTH,
                    DateType::NEXT_WEEK,
                    DateType::NEXT_MONTH,
                ],
            ],
        ];

        foreach ($this->arParams['FILTER_COLUMNS'] as $filterColumn) {
            if (!in_array($filterColumn, array_keys($allFilterColumns))) {
                continue;
            }

            $resultFilterColumns[] = $allFilterColumns[$filterColumn];
        }

        return $resultFilterColumns;
    }

    protected function getDataFilter(): array
    {
        if (empty($this->arResult['FILTERS'])) {
            return [];
        }

        $filterOptions = new FilterOptions($this->arResult['FILTER_ID']);
        $requestFilter = $filterOptions->getFilter($this->arResult['FILTERS']);

        $filter = [];
        if (!empty($requestFilter['id']) && in_array('id', $this->arParams['FILTER_COLUMNS'])) {
            $filter['=id'] = $requestFilter['id'];
        }

        if (!empty($requestFilter['code']) && in_array('code', $this->arParams['FILTER_COLUMNS'])) {
            $filter['=code'] = $requestFilter['code'];
        }

        if (in_array('course', $this->arParams['FILTER_COLUMNS'])) {
            if (!empty($requestFilter['course_from'])) {
                $filter['>=course'] = $requestFilter['course_from'];
            }
            if (!empty($requestFilter['course_to'])) {
                $filter['<=course'] = $requestFilter['course_to'];
            }
        }

        if (in_array('date', $this->arParams['FILTER_COLUMNS'])) {
            if (!empty($requestFilter['date_from'])) {
                $filter['>=date'] = $requestFilter['date_from'];
            }
            if (!empty($requestFilter['date_to'])) {
                $filter['<=date'] = $requestFilter['date_to'];
            }
        }

        return $filter;
    }

    private function getGridOrder(): array
    {
        $defaultSort = ['id' => 'DESC'];

        $gridOptions = new \Bitrix\Main\Grid\Options($this->arParams['GRID_ID']);
        $sorting = $gridOptions->getSorting(['sort' => $defaultSort]);

        $by = key($sorting['sort']);
        $order = strtoupper(current($sorting['sort'])) === 'ASC' ? 'ASC' : 'DESC';

        $list = [];
        foreach ($this->getGridColumns() as $column) {
            if (!empty($column['sort'])) {
                $list[] = $column['sort'];
            }
        }

        if (!in_array($by, $list)) {
            return $defaultSort;
        }

        return [$by => $order];
    }

    private function getGridColumns(): array
    {
        return [
            [
                'id'   => 'id',
                'name' => 'id',
                'sort' => 'id',
            ],
            [
                'id'      => 'code',
                'name'    => 'code',
                'default' => true,
                'sort'    => 'code',
            ],
            [
                'id'      => 'course',
                'name'    => 'course',
                'default' => true,
                'sort'    => 'course',
            ],
            [
                'id'      => 'date',
                'name'    => 'date',
                'default' => true,
                'sort'    => 'date',
            ],
        ];
    }

    protected function getRows(array $items): array
    {
        $rows = [];

        foreach ($items as $item) {
            $rows[] = ['columns' => array_map(fn($v) => $v, $item)];
        }

        return $rows;
    }
}
