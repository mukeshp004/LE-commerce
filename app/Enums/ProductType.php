<?php

namespace App\Enums;

enum ProductType: int
{
    case SIMPLE = 1;
    case CONFIGURABLE = 2;
    case GROUPED = 3;
    case VIRTUAL = 4;
    case DOWNLOADABLE = 5;
    case BOOKING = 6;
    case BUNDLE = 7;
    case GIFT_CARD = 8;

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            ProductType::SIMPLE => 'Simple',
            ProductType::CONFIGURABLE => 'Configurable',
            ProductType::GROUPED => 'Grouped',
        };
    }
}
