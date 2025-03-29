<?php

namespace Modules\Categories\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Categories\Models\Category;
use Modules\Categories\Transformers\CategoryResource;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount("products")->active()->get();

        return api()->records(CategoryResource::collection($categories));
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Category $category): JsonResponse
    {
        if ($request->has("withProducts")) {
            $category->loadMissing("products");
        }

        return api()->record(new CategoryResource($category));
    }
}
