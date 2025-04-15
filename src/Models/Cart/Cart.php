<?php

declare(strict_types=1);

namespace Lukas\KoskampShoppingCart\Models\Cart;

use Lukas\KoskampShoppingCart\Models\Discount\Discount;
use Lukas\KoskampShoppingCart\Models\Product\Product;

class Cart
{
    private const DEFAULT_TOTAL = 0;

    /** @var CartItem[]  */
    private array $cartItems = [];

    private ?Discount $discount = null;

    public function addProduct(Product $product, int $quantity): void
    {
        foreach ($this->cartItems as $cartItem) {
            if ($product->getProductNumber() === $cartItem->getProduct()->getProductNumber()) {
                $cartItem->setAmount($cartItem->getAmount() + $quantity);

                return;
            }
        }

        $this->cartItems[] = new CartItem($product, $quantity);
    }

    public function removeProduct(string $objectNumber): void
    {
        $this->cartItems = array_filter(
            $this->cartItems,
            fn($cartItem) => $cartItem->getProduct()->getProductNumber() !== $objectNumber
        );
    }

    public function updateProductQuantity(string $objectNumber, int $quantity): void
    {
        foreach ($this->cartItems as $cartItem) {
            if ($cartItem->getProduct()->getProductNumber() === $objectNumber) {
                $cartItem->setAmount($quantity);
                break;
            }
        }
    }

    public function calculateTotalCost(bool $withoutDiscount = false): float
    {
        $finalPrice = self::DEFAULT_TOTAL;

        if (!empty($this->getCartItems())) {
            foreach ($this->cartItems as $cartItem) {
                $finalPrice += $cartItem->calculateTotalPrice();
            }
        }

        if ($this->discount !== null && !$withoutDiscount) {
            $finalPrice = $this->discount->apply($finalPrice);
        }

        return $finalPrice;
    }

    public function clearCart(): void
    {
        $this->setCartItems([]);
        $this->setDiscount(null);
    }

    public function getCartItems(): array {
        return $this->cartItems;
    }

    public function setCartItems(array $cartItems): self
    {
        $this->cartItems = $cartItems;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }
}
