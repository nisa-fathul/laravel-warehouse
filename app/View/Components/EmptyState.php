<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmptyState extends Component
{
    public string $title;
    public string $message;
    public string $icon;

    public function __construct(
        string $title = 'No Data Found',
        string $message = 'There is currently no data available.',
        string $icon = 'bi-database-x'
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
    }

    public function render(): View|Closure|string
    {
        return view('components.empty-state');
    }
}
