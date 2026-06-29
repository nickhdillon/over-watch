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
    case NEUTRAL = 'neutral';
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
            self::NEUTRAL => 'Neutral',
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

    public function background(): string
    {
        return match ($this) {
            self::AMBER => 'bg-amber-500',
            self::BLUE => 'bg-blue-500',
            self::CYAN => 'bg-cyan-500',
            self::EMERALD => 'bg-emerald-500',
            self::FUCHSIA => 'bg-fuchsia-500',
            self::GREEN => 'bg-green-500',
            self::INDIGO => 'bg-indigo-500',
            self::LIME => 'bg-lime-500',
            self::NEUTRAL => 'bg-neutral-500',
            self::ORANGE => 'bg-orange-500',
            self::PINK => 'bg-pink-500',
            self::PURPLE => 'bg-purple-500',
            self::RED => 'bg-red-500',
            self::ROSE => 'bg-rose-500',
            self::SKY => 'bg-sky-500',
            self::TEAL => 'bg-teal-500',
            self::VIOLET => 'bg-violet-500',
            self::YELLOW => 'bg-yellow-500'
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->map(fn (self $color): array => [
                'value' => $color->value,
                'label' => $color->label(),
                'swatch' => "bg-{$color->value}-500",
            ])
            ->all();
    }
}
