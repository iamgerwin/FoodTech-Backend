<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\RestaurantBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function nearby(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        if (!$customer || !$customer->latitude || !$customer->longitude) {
            return response()->json(['message' => 'Customer location not set.'], 422);
        }
        $lat = $customer->latitude;
        $lng = $customer->longitude;
        $radius = $request->input('radius', 5); // default 5km

        $branches = RestaurantBranch::query()
            ->where('is_active', true)
            ->where('accepts_orders', true)
            ->selectRaw('*, (
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance', [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->paginate(15);

        return response()->json($branches);
    }
}
