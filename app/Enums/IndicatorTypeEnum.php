<?php

namespace App\Enums;

enum IndicatorTypeEnum: string
{
    case Value = 'значение';
    case Constant = 'константа';
    case RadioButtons = 'радио-кнопки';


    public function id(): int
    {
        return match ($this->value) {
            self::Value->value  => 1,
            self::Constant->value  => 2,
            self::RadioButtons->value  => 3,
        };
    }
}
