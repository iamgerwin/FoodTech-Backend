<?php

namespace App\Http\Controllers\Api\V1;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('branches/nearby', [BranchController::class, 'nearby']);
});
