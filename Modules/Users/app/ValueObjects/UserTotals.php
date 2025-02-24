<?php

namespace Modules\Users\ValueObjects;

final class UserTotals
{
    /**
     * construct object
     */
    public function __construct(
        public int $cartItems,
        public int $wishlistItems,
        public int $comparedItems,
        public int $orders,
        public int $totalPurchased,
    ) {}

    /**
     * Convert the object to an array
     * @return array{cartItems: int, comparedItems: int, orders: int, totalPurchased: int, wishlistItems: int}
     */
    public function toArray(): array
    {
        return [
            'cartItems' => $this->cartItems,
            'wishlistItems' => $this->wishlistItems,
            'comparedItems' => $this->comparedItems,
            'orders' => $this->orders,
            'totalPurchased' => $this->totalPurchased,
        ];
    }

    /**
     * Create a Totals object from an array
     * @param array{cartItems: int, comparedItems: int, orders: int, totalPurchased: int, wishlistItems: int} $data
     * @return UserTotals
     */
    public static function fromArray(array $data): self
    {
        return new self(
            cartItems: $data['cartItems'],
            wishlistItems: $data['wishlistItems'],
            comparedItems: $data['comparedItems'],
            orders: $data['orders'],
            totalPurchased: $data['totalPurchased'],
        );
    }

    /**
     * get default totals
     * @return UserTotals
     */
    public static function default(): self
    {
        return new self(
            cartItems: 0,
            wishlistItems: 0,
            comparedItems: 0,
            orders: 0,
            totalPurchased: 0,
        );
    }

    /**
     * recalculate totals
     * @return UserTotals
     */
    public static function recalculate(): self
    {
        // todo implement calculations
        return new self(
            cartItems: 0,
            wishlistItems: 0,
            comparedItems: 0,
            orders: 0,
            totalPurchased: 0,
        );
    }

    /**
     * turn object to json string
     * @return bool|string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
