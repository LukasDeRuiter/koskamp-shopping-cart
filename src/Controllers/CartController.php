<?php

declare(strict_types=1);

namespace Lukas\KoskampShoppingCart\Controllers;

use Lukas\KoskampShoppingCart\Models\Cart\CartItem;
use Lukas\KoskampShoppingCart\Models\Discount\Discount;
use Lukas\KoskampShoppingCart\Models\Product\Product;
use Lukas\KoskampShoppingCart\Models\Cart\Cart;

class CartController
{
    /** @var Product[]  */
    private array $products;

    public function __construct()
    {
        $this->products = [
            'test-product-1' => new Product('test-product-1', 'Product 1', 15.99),
            'test-product-2' => new Product('test-product-2', 'Product 2', 10.0),
            'test-product-3' => new Product('test-product-3', 'Product 3', 29.99)
        ];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = new Cart();
        }
    }

    public function index(): void
    {
        $cart = $_SESSION['cart'];
        $cartItems = $cart->getCartItems();
        $total = $cart->calculateTotalCost();

        include __DIR__ . '/../../templates/cart.phtml';
    }

    public function add(string $objectNumber, ?string $discount): void
    {
        header('Content-Type: application/json');

        if ($objectNumber && isset($this->products[$objectNumber])) {
            $_SESSION['cart']->addProduct($this->products[$objectNumber], 1);
            $cart = $_SESSION['cart'];

            if ($discount !== null) {
                $discount  = new Discount(floatval($discount));
                $cart->setDiscount($discount);
            }

            $this->formatCartJson($cart);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid ObjectNumber']);
        }

        exit;
    }

    public function remove(string $objectNumber, ?string $discount): void
    {
        header('Content-Type: application/json');

        if ($objectNumber) {
            $_SESSION['cart']->removeProduct($objectNumber);
            $cart = $_SESSION['cart'];

            if ($discount !== null) {
                $discount  = new Discount(floatval($discount));
                $cart->setDiscount($discount);
            }

            $this->formatCartJson($cart);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid ObjectNumber']);
        }

        exit;
    }

    public function updateAmount(string $objectNumber, ?string $discount, string $amount): void
    {
        header('Content-Type: application/json');

        if ($objectNumber && isset($this->products[$objectNumber])) {
            $_SESSION['cart']->updateProductQuantity($objectNumber, (int)$amount);
            $cart = $_SESSION['cart'];

            if ($discount !== null) {
                $discount  = new Discount(floatval($discount));
                $cart->setDiscount($discount);
            }

            $this->formatCartJson($cart);

        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid ObjectNumber']);
        }

        exit;
    }

    public function clear(): void
    {
        header('Content-Type: application/json');

        if (isset($_SESSION['cart'])) {
            $_SESSION['cart']->clearCart();
        }

        echo json_encode(['success' => true]);

        exit;
    }

    private function formatCartJson(Cart $cart): void
    {
        $cartItems = [];

        /** @var CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            $cartItems[] = [
                'productNumber' => $cartItem->getProduct()->getProductNumber(),
                'amount' => $cartItem->getAmount(),
                'total' => $cartItem->calculateTotalPrice(),
            ];
        }

        echo json_encode([
            'success' => true,
            'cartItems' => $cartItems,
            'totalItems' => count($cart->getCartItems()),
            'totalCost' => $cart->calculateTotalCost(),
            'discount' => $cart->getDiscount() ? $cart->getDiscount()->getPercentage() : 0,
            'totalCostWithoutDiscount' => $cart->calculateTotalCost(true)
        ]);
    }
}
