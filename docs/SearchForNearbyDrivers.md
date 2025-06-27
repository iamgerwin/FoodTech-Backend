# SearchForNearbyDrivers Job Documentation

**Location:** `app/Jobs/SearchForNearbyDrivers.php`

## Overview

The `SearchForNearbyDrivers` job locates available drivers within a specified radius of a given latitude and longitude. It supports both SQL-based Haversine distance calculation (for supported databases) and a PHP fallback for SQLite. Optionally, you can set a limit on the number of drivers returned.

## Main Methods

- `__construct(float $latitude, float $longitude, float $radiusKm = 5, ?int $limit = null)`
  - Initializes the job with search coordinates, radius (in km), and an optional limit.

- `handle(): Collection`
  - Executes the search and returns a collection of nearby available drivers.

- `distanceKm($lat1, $lon1, $lat2, $lon2): float`
  - Calculates the Haversine distance between two coordinates in kilometers.

- `static progressiveSearch(
    float $latitude,
    float $longitude,
    float $initialRadiusKm = 5,
    float $maxRadiusKm = 20,
    float $radiusStepKm = 5,
    int $intervalMinutes = 2,
    ?int $limit = null
  ): Collection`
  - Expands the search radius progressively until drivers are found or the max radius is reached.

## Usage

- Dispatch this job to search for drivers near a location.
- Use `progressiveSearch` for a retry/expanding search pattern.

---

For further details, see the source code or contact the backend team.
