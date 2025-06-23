<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\BarChartWidget;

class CustomerExpensesChart extends BarChartWidget
{
    protected static ?string $heading = 'Customer Expenses';

    protected function getData(): array
    {
        // Group orders by customer and sum their total_amount
        $expenses = Order::query()
            ->selectRaw('customer_id, SUM(total_amount) as total_expense')
            ->groupBy('customer_id')
            ->with('customer')
            ->orderByDesc('total_expense')
            ->limit(10)
            ->get();

        $labels = $expenses->map(function ($order) {
            // Show customer name if available, otherwise ID
            return optional($order->customer)->name ?? $order->customer_id;
        })->toArray();
        $data = $expenses->pluck('total_expense')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Expenses',
                    'data' => $data,
                    'backgroundColor' => '#f59e42',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
