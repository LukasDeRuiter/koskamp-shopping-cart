<?php

declare(strict_types=1);

namespace Lukas\KoskampShoppingCart\Models\Cart;

use Lukas\KoskampShoppingCart\Models\Product\Product;

class CartItem
{
    public function __construct(
        private Product $product,
        private int $amount
    )
    {}

    public function calculateTotalPrice(): float
    {
        return $this->getProduct()->getPrice() * $this->getAmount();
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
