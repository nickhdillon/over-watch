<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class OptionPicker extends Component
{
    public function __construct(
        public string $label,
        public string $model,
        public array $options,
        public mixed $value = null,
        public string $placeholder = 'Choose...',
        public bool $nullable = false,
    ) {}

    public function render(): View
    {
        return view('components.option-picker');
    }
}
