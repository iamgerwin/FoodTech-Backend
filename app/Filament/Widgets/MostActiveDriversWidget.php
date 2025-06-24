<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use Filament\Widgets\BarChartWidget;

class MostActiveDriversWidget extends BarChartWidget
{
    protected static ?string $heading = 'Most Active Drivers';

    protected function getData(): array
    {
        $drivers = Driver::query()
            ->orderByDesc('total_deliveries')
            ->limit(10)
            ->with('user')
            ->get();

        $labels = $drivers->map(function ($driver) {
            return optional($driver->user)->name ?? $driver->id;
        })->toArray();
        $data = $drivers->pluck('total_deliveries')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Deliveries',
                    'data' => $data,
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $labels,
        ];
    }

    public static function canView(): bool
    {
        return true; // Adjust as needed for admin only
    }
}
