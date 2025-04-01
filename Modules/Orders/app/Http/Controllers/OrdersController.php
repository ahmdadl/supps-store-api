<?php

namespace Modules\Orders\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Orders\Models\Order;
use Modules\Orders\Transformers\OrderResource;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = Order::where("user_id", user()?->id)->latest()->paginate();

        return api()->paginate($orders, OrderResource::class);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $order->loadMissing([
            "address",
            "coupon",
            "paymentAttempts",
            "items",
            "items.product",
        ]);

        return api()->record(new OrderResource($order));
    }
}
