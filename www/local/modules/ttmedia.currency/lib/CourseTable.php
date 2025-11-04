<?php
namespace Ttmedia\Currency;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Type\DateTime;

class CourseTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'currency_course';
    }

    public static function getMap(): array
    {
        return [
            'id' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
            ],
            'code' => [
                'data_type' => 'string',
            ],
            'date' => [
                'data_type' => 'datetime',
                'required' => true,
                'default_value' => new DateTime(),
            ],
            'course' => [
                'data_type' => 'float',
                'required' => true,
            ],
        ];
    }
}
