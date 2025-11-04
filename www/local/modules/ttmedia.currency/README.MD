пример вызова компонента

```php

$APPLICATION->IncludeComponent(
    "currency:list",
    '.default',
    [
        'FILTER_COLUMNS' => [
//            'id',
            'course',
            'date',
            'code',
        ],
        'GRID_ID'        => 'cur_grid',
        'PER_PAGE'       => 10,
    ],
);
```

если отсутствует или пустой FILTER_COLUMNS, то компонент currency:filter не подключиться