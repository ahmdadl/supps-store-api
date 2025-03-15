<?php

namespace Modules\Carts\Actions;

use Modules\Addresses\Models\Address;
use Modules\Carts\Services\CartService;

class SetCartAddressAction
{
    public function __construct(public readonly CartService $cartService) {}
    public function handle(Address $address): void
    {
        $this->cartService->setAddress($address);
    }
}
