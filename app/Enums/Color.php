<?php

declare(strict_types=1);

namespace App\Enums;

enum Color: string
{
    case AMBER = 'amber';
    case BLUE = 'blue';
    case CYAN = 'cyan';
    case EMERALD = 'emerald';
    case FUCHSIA = 'fuchsia';
    case GREEN = 'green';
    case INDIGO = 'indigo';
    case LIME = 'lime';
    case ORANGE = 'orange';
    case PINK = 'pink';
    case PURPLE = 'purple';
    case RED = 'red';
    case ROSE = 'rose';
    case SKY = 'sky';
    case TEAL = 'teal';
    case VIOLET = 'violet';
    case YELLOW = 'yellow';

    public function label(): string
    {
        return match ($this) {
            self::AMBER => 'Amber',
            self::BLUE => 'Blue',
            self::CYAN => 'Cyan',
            self::EMERALD => 'Emerald',
            self::FUCHSIA => 'Fuchsia',
            self::GREEN => 'Green',
            self::INDIGO => 'Indigo',
            self::LIME => 'Lime',
            self::ORANGE => 'Orange',
            self::PINK => 'Pink',
            self::PURPLE => 'Purple',
            self::RED => 'Red',
            self::ROSE => 'Rose',
            self::SKY => 'Sky',
            self::TEAL => 'Teal',
            self::VIOLET => 'Violet',
            self::YELLOW => 'Yellow'
        };
    }
}
