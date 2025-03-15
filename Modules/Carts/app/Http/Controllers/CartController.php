<?php

namespace Modules\Carts\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Addresses\Transformers\AddressResource;
use Modules\Carts\Actions\AddToCartAction;
use Modules\Carts\Actions\RemoveFromCartAction;
use Modules\Carts\Actions\UpdateCartAction;
use Modules\Carts\Models\CartItem;
use Modules\Carts\Transformers\CartResource;
use Modules\Products\Models\Product;
use Modules\Carts\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Addresses\Models\Address;

class CartController extends Controller
{
    /**
     * get all cart details
     */
    public function index(
        Request $request,
        CartService $cartService
    ): JsonResponse {
        $cartService->cart->loadMissing(["coupon", "address", "items"]);

        $response = [];

        $response["cart"] = new CartResource($cartService->cart);

        $loadedArray = $request->array("with") ?? [];

        if (in_array("addresses", $loadedArray)) {
            $response["addresses"] = AddressResource::collection(
                Address::where("user_id", user()->id)
                    ->with(["government", "city"])
                    ->get()
            );
        }

        return api()->success($response);
    }

    /**
     * add product to cart
     */
    public function add(
        Request $request,
        Product $product,
        AddToCartAction $action
    ): JsonResponse {
        $action->handle($product, $request->integer("quantity", 1));

        return $this->index($request, $action->cartService);
    }

    /**
     * update cart item
     */
    public function update(
        Request $request,
        CartItem $cartItem,
        UpdateCartAction $action
    ): JsonResponse {
        $action->handle($cartItem, $request->integer("quantity", 1));

        return $this->index($request, $action->cartService);
    }

    /**
     * update cart product
     */
    public function updateByProduct(
        Request $request,
        Product $product,
        UpdateCartAction $action
    ): JsonResponse {
        $action->usingProduct($product, $request->integer("quantity", 1));

        return $this->index($request, $action->cartService);
    }

    /**
     * remove item from cart
     */
    public function remove(
        Request $request,
        CartItem $cartItem,
        RemoveFromCartAction $action
    ): JsonResponse {
        $action->handle($cartItem);

        return $this->index($request, $action->cartService);
    }

    /**
     * remove product from cart
     */
    public function removeByProduct(
        Request $request,
        Product $product,
        RemoveFromCartAction $action
    ): JsonResponse {
        $action->usingProduct($product);

        return $this->index($request, $action->cartService);
    }
}
