<?php

// namespace App\Filament\Widgets;

// use App\Models\PreOrder;
// use App\Models\Team;
// use Filament\Widgets\Widget;
// use Illuminate\Contracts\View\View;

// class DashboardWidget extends Widget
// {
//     protected static ?string $heading = 'Dashboard Overview';
//     protected int | string | array $columnSpan = 'full';

//     public $preOrders;
//     public $teams;

//     public function mount(): void
//     {
//         $this->preOrders = PreOrder::with(['product', 'user', 'product.supplier'])->get();
//         $this->teams = Team::all();
//     }

//     public function render(): View
//     {
//         return view('filament.widgets.dashboard-widget');
//     }
// }
