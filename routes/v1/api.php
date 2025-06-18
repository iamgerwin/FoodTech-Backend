<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BranchController;

Route::middleware('clerk')->prefix('v1')->group(function () {
    Route::get('branches/nearby', [BranchController::class, 'nearby']);
    // Add other protected endpoints here
});
