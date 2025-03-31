<?php

namespace Modules\Addresses\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Addresses\Http\Requests\CreateAddressRequest;
use Modules\Addresses\Http\Requests\UpdateAddressRequest;
use Modules\Addresses\Models\Address;
use Modules\Addresses\Transformers\AddressResource;

class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $addresses = Address::where("user_id", user()?->id)->latest()->get();

        return api()->records(AddressResource::collection($addresses));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAddressRequest $request): JsonResponse
    {
        $address = Address::create([
            "user_id" => user()->id,
            ...$request->validated(),
        ]);

        return api()->record(new AddressResource($address));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateAddressRequest $request,
        Address $address
    ): JsonResponse {
        $address->update($request->validated());

        return api()->record(new AddressResource($address));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address): JsonResponse
    {
        $address->delete();

        return api()->noContent();
    }
}
