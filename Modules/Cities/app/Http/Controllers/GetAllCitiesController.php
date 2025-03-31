<?php

namespace Modules\Cities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Cities\Models\City;
use Modules\Cities\Transformers\CityResource;

class GetAllCitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(): JsonResponse
    {
        return api()->records(
            CityResource::collection(City::active()->paginateIfRequested())
        );
    }
}
