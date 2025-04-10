<?php

namespace Modules\Cities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Cities\Models\City;
use Modules\Cities\Transformers\CityResource;

class GetAllCitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $cities = City::active();

        $cities->when(
            $request->integer("government_id"),
            fn($q, int $governmentId) => $q->where(
                "government_id",
                $governmentId
            )
        );

        /** @var LengthAwarePaginator|Collection */
        $cities = $cities->paginateIfRequested();

        return api()->paginatedIfRequested($cities, CityResource::class);
    }
}
