<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case TO_DO = 'to-do';
    case IN_PROGRESS = 'in-progress';
    case IN_REVIEW = 'in-review';
    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::TO_DO => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::IN_REVIEW => 'In Review',
            self::DONE => 'Done'
        };
    }

    public function textColors(): string
    {
        return match ($this) {
            self::TO_DO => 'text-neutral-700 dark:text-neutral-200',
            self::IN_PROGRESS => 'text-sky-700 dark:text-sky-300',
            self::IN_REVIEW => 'text-indigo-700 dark:text-indigo-300',
            self::DONE => 'text-emerald-700 dark:text-emerald-300',
        };
    }

    public function bgColors(): string
    {
        return match ($this) {
            self::TO_DO => 'bg-neutral-200 dark:bg-neutral-400/40',
            self::IN_PROGRESS => 'bg-sky-400/10 dark:bg-sky-400/20',
            self::IN_REVIEW => 'bg-indigo-400/10 dark:bg-indigo-400/20',
            self::DONE => 'bg-emerald-400/10 dark:bg-emerald-400/20',
        };
    }

    public function bgHoverColors(): string
    {
        return match ($this) {
            self::TO_DO => 'hover:bg-neutral-100 dark:hover:bg-neutral-400/10',
            self::IN_PROGRESS => 'hover:bg-sky-400/10 dark:hover:bg-sky-400/10',
            self::IN_REVIEW => 'hover:bg-indigo-400/10 dark:hover:bg-indigo-400/10',
            self::DONE => 'hover:bg-emerald-400/10 dark:hover:bg-emerald-400/10',
        };
    }

    public function borderColors(): string
    {
        return match ($this) {
            self::TO_DO => 'border border-neutral-300 dark:border-neutral-400',
            self::IN_PROGRESS => 'border border-sky-200 dark:border-sky-700',
            self::IN_REVIEW => 'border border-indigo-200 dark:border-indigo-700',
            self::DONE => 'border border-emerald-200 dark:border-emerald-700',
        };
    }

    public function indicatorColors(): string
    {
        return match ($this) {
            self::TO_DO => 'bg-neutral-300 dark:bg-neutral-200',
            self::IN_PROGRESS => 'bg-sky-300 dark:bg-sky-600',
            self::IN_REVIEW => 'bg-indigo-300 dark:bg-indigo-600',
            self::DONE => 'bg-emerald-300 dark:bg-emerald-600',
        };
    }
}
