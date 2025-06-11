<?php

namespace App\Jobs;

use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SearchForNearbyDrivers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected float $latitude;
    protected float $longitude;
    protected float $radiusKm;
    protected ?int $limit;

    /**
     * Create a new job instance.
     */
    public function __construct(float $latitude, float $longitude, float $radiusKm = 5, ?int $limit = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->radiusKm = $radiusKm;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     */
    public function handle(): Collection
    {
        $connection = \DB::connection()->getDriverName();
        if ($connection === 'sqlite') {
            // Fallback: fetch all available drivers, filter in PHP
            $drivers = Driver::where('is_available', true)
                ->whereNotNull('current_latitude')
                ->whereNotNull('current_longitude')
                ->get();
            $filtered = $drivers->filter(function ($driver) {
                return $this->distanceKm($this->latitude, $this->longitude, $driver->current_latitude, $driver->current_longitude) <= $this->radiusKm;
            })->values();
            if ($this->limit) {
                return $filtered->take($this->limit);
            }
            return $filtered;
        }
        // Default: use SQL Haversine
        $haversine = "(6371 * acos(cos(radians(?) ) * cos(radians(current_latitude)) * cos(radians(current_longitude) - radians(?)) + sin(radians(?)) * sin(radians(current_latitude))))";
        $query = Driver::query()
            ->select('*')
            ->selectRaw("{$haversine} AS distance", [$this->latitude, $this->longitude, $this->latitude])
            ->where('is_available', true)
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->having('distance', '<=', $this->radiusKm)
            ->orderBy('distance');
        if ($this->limit) {
            $query->limit($this->limit);
        }
        return $query->get();
    }

    /**
     * Haversine distance in km.
     */
    protected function distanceKm($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371;
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $deltaLat = $lat2 - $lat1;
        $deltaLon = deg2rad($lon2 - $lon1);
        $a = sin($deltaLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    /**
     * Progressive search: expand radius every interval until drivers found or max radius reached.
     * @param float $latitude
     * @param float $longitude
     * @param float $initialRadiusKm
     * @param float $maxRadiusKm
     * @param float $radiusStepKm
     * @param int $intervalMinutes
     * @param int|null $limit
     * @return Collection
     */
    public static function progressiveSearch(
        float $latitude,
        float $longitude,
        float $initialRadiusKm = 5,
        float $maxRadiusKm = 20,
        float $radiusStepKm = 5,
        int $intervalMinutes = 2,
        ?int $limit = null
    ): Collection {
        $radius = $initialRadiusKm;
        $drivers = collect();
        while ($radius <= $maxRadiusKm) {
            $job = new self($latitude, $longitude, $radius, $limit);
            $drivers = $job->handle();
            if ($drivers->count() > 0) {
                break;
            }
            $radius += $radiusStepKm;
            // In real async, you'd wait $intervalMinutes before next try
            // For sync use (like in tests), we just loop
        }
        return $drivers;
    }
     */
    public function handle(): Collection
    {
        $haversine = "(6371 * acos(cos(radians(?) ) * cos(radians(current_latitude)) * cos(radians(current_longitude) - radians(?)) + sin(radians(?)) * sin(radians(current_latitude))))";

        $query = Driver::query()
            ->select('*')
            ->selectRaw("{$haversine} AS distance", [$this->latitude, $this->longitude, $this->latitude])
            ->where('is_available', true)
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->having('distance', '<=', $this->radiusKm)
            ->orderBy('distance');

        if ($this->limit) {
            $query->limit($this->limit);
        }

        return $query->get();
    }
}
