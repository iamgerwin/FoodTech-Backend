<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\OrderItemVariant;
use Filament\Widgets\BarChartWidget;
use Filament\Forms;
use Illuminate\Support\Carbon;

class MostPopularOrderItemsWidget extends BarChartWidget
{
    protected static ?string $heading = 'Most Popular Items / Variants';

    public ?string $range = 'today';
    public ?string $type = 'item';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('range')
                ->label('Time Range')
                ->options([
                    'today' => 'Today',
                    '12h' => 'Last 12 Hours',
                    '3d' => 'Last 3 Days',
                    '7d' => 'Last 7 Days',
                    '30d' => 'Last 30 Days',
                    '6m' => 'Last 6 Months',
                    '1y' => 'Last 1 Year',
                ])
                ->default('today'),
            Forms\Components\Select::make('type')
                ->label('Type')
                ->options([
                    'item' => 'Order Item',
                    'variant' => 'Order Item Variant',
                ])
                ->default('item'),
        ];
    }

    protected function getData(): array
    {
        $now = now();
        $from = match ($this->range) {
            'today' => $now->copy()->startOfDay(),
            '12h' => $now->copy()->subHours(12),
            '3d' => $now->copy()->subDays(3),
            '7d' => $now->copy()->subDays(7),
            '30d' => $now->copy()->subDays(30),
            '6m' => $now->copy()->subMonths(6),
            '1y' => $now->copy()->subYear(),
            default => $now->copy()->startOfDay(),
        };

        if ($this->type === 'item') {
            $query = OrderItem::query()
                ->where('created_at', '>=', $from)
                ->selectRaw('menu_item_id, SUM(quantity) as qty')
                ->groupBy('menu_item_id')
                ->orderByDesc('qty')
                ->with('menuItem')
                ->limit(10)
                ->get();

            $labels = $query->map(fn($row) => optional($row->menuItem)->name ?? $row->menu_item_id)->toArray();
            $data = $query->pluck('qty')->toArray();
            $label = 'Items Sold';
        } else {
            $query = OrderItemVariant::query()
                ->where('created_at', '>=', $from)
                ->selectRaw('name, COUNT(*) as qty')
                ->groupBy('name')
                ->orderByDesc('qty')
                ->limit(10)
                ->get();

            $labels = $query->pluck('name')->toArray();
            $data = $query->pluck('qty')->toArray();
            $label = 'Variants Sold';
        }

        return [
            'datasets' => [
                [
                    'label' => $label,
                    'data' => $data,
                    'backgroundColor' => '#6366f1',
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
