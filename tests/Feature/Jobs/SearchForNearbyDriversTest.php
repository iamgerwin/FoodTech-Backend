<?php

declare(strict_types=1);

use App\Jobs\SearchForNearbyDrivers;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses sqlite for testing', function () {
    expect(DB::connection()->getDriverName())->toBe('sqlite');
});

describe('SearchForNearbyDrivers Job', function () {
    beforeEach(function () {
        // Manila
        Driver::factory()->create([
            'current_latitude' => 14.5995,
            'current_longitude' => 120.9842,
            'is_available' => true,
        ]);
        // Quezon City (close to Manila)
        Driver::factory()->create([
            'current_latitude' => 14.6760,
            'current_longitude' => 121.0437,
            'is_available' => true,
        ]);
        // Cebu (far from Manila)
        Driver::factory()->create([
            'current_latitude' => 10.3157,
            'current_longitude' => 123.8854,
            'is_available' => true,
        ]);
        // Inactive driver in Manila
        Driver::factory()->create([
            'current_latitude' => 14.5995,
            'current_longitude' => 120.9842,
            'is_available' => false,
        ]);
    });

    it('finds only available drivers within radius', function () {
        $latitude = 14.5995; // Manila
        $longitude = 120.9842;
        $radiusKm = 10; // 10km should include Manila and Quezon City, not Cebu

        $job = new SearchForNearbyDrivers($latitude, $longitude, $radiusKm);
        $drivers = $job->handle();

        expect($drivers)->toHaveCount(2);
        $driverLocations = $drivers->map(fn($d) => [$d->current_latitude, $d->current_longitude])->toArray();
        expect($driverLocations)->toContain([14.5995, 120.9842]); // Manila
        expect($driverLocations)->toContain([14.6760, 121.0437]); // Quezon City
        // Cebu and inactive driver should not be included
        foreach ($drivers as $driver) {
            expect($driver->is_available)->toBeTrue();
        }
    });

    it('progressively expands radius until a driver is found or max radius is reached', function () {
        $latitude = 14.5995; // Manila
        $longitude = 120.9842;
        $initialRadiusKm = 1; // Too small for any driver except Manila
        $radiusStepKm = 10;
        $maxRadiusKm = 400; // Large enough to eventually include Cebu

        // Should find only Manila at 1km
        $drivers1 = SearchForNearbyDrivers::progressiveSearch($latitude, $longitude, $initialRadiusKm, $maxRadiusKm, $radiusStepKm);
        $locations1 = $drivers1->map(fn($d) => [$d->current_latitude, $d->current_longitude])->toArray();
        expect($locations1)->toContain([14.5995, 120.9842]);
        expect($locations1)->not->toContain([10.3157, 123.8854]); // Cebu
        foreach ($drivers1 as $driver) {
            expect($driver->is_available)->toBeTrue();
        }

        // Move search origin to Cebu, set radius so only Cebu is found
        $latitude = 10.3157; // Cebu
        $longitude = 123.8854;
        $initialRadiusKm = 1;
        $radiusStepKm = 10;
        $maxRadiusKm = 20;
        $drivers2 = SearchForNearbyDrivers::progressiveSearch($latitude, $longitude, $initialRadiusKm, $maxRadiusKm, $radiusStepKm);
        $locations2 = $drivers2->map(fn($d) => [$d->current_latitude, $d->current_longitude])->toArray();
        expect($locations2)->toContain([10.3157, 123.8854]); // Cebu
        expect($locations2)->not->toContain([14.5995, 120.9842]); // Manila
        foreach ($drivers2 as $driver) {
            expect($driver->is_available)->toBeTrue();
        }

        // If no drivers are available, should return empty
        Driver::query()->update(['is_available' => false]);
        $drivers3 = SearchForNearbyDrivers::progressiveSearch($latitude, $longitude, $initialRadiusKm, $maxRadiusKm, $radiusStepKm);
        expect($drivers3)->toHaveCount(0);
    });
});
