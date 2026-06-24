<?php

declare(strict_types=1);

namespace App\Enums;

enum Priority: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';

    public function label(): string
    {
        return match ($this) {
            self::HIGH => 'High',
            self::MEDIUM => 'Medium',
            self::LOW => 'Low'
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::HIGH => 'text-red-500',
            self::MEDIUM => 'text-amber-500',
            self::LOW => 'text-blue-600'
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::HIGH => 'chevron-double-up',
            self::MEDIUM => 'bars-2',
            self::LOW => 'chevron-double-down',
        };
    }

    public function bgHoverColors(): string
    {
        return match ($this) {
            self::HIGH => 'hover:bg-red-400/10 dark:hover:bg-red-400/10',
            self::MEDIUM => 'hover:bg-amber-400/10 dark:hover:bg-amber-400/10',
            self::LOW => 'hover:bg-blue-400/10 dark:hover:bg-blue-400/10',
        };
    }
}
