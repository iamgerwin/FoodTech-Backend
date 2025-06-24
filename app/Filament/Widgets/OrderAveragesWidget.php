<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\PieChartWidget;

class OrderAveragesWidget extends PieChartWidget
{
    protected static ?string $heading = 'Order Averages';

    protected function getData(): array
    {
        $orders = Order::with(['branch', 'deliveryAddress'])
            ->whereNotNull('delivered_at')
            ->whereNotNull('placed_at')
            ->get();

        $distanceSum = 0;
        $timeSum = 0;
        $count = 0;

        foreach ($orders as $order) {
            $branch = $order->branch;
            $address = $order->deliveryAddress;
            if ($branch && $address && $branch->latitude && $branch->longitude && $address->latitude && $address->longitude) {
                $distance = $this->haversineDistance($branch->latitude, $branch->longitude, $address->latitude, $address->longitude);
                $distanceSum += $distance;
                $time = strtotime($order->delivered_at) - strtotime($order->placed_at);
                $timeSum += $time;
                $count++;
            }
        }

        $averageDistance = $count ? round($distanceSum / $count, 2) : 0;
        $averageTime = $count ? round($timeSum / $count / 60, 1) : 0; // in minutes

        return [
            'datasets' => [
                [
                    'label' => 'Order Averages',
                    'data' => [$averageDistance, $averageTime],
                    'backgroundColor' => ['#6366f1', '#10b981'],
                ],
            ],
            'labels' => ['Avg Distance (km)', 'Avg Time (min)'],
        ];
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

}
