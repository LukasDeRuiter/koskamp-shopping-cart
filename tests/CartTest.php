<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Lukas\KoskampShoppingCart\Models\Cart\Cart;
use Lukas\KoskampShoppingCart\Models\Product\Product;
use Lukas\KoskampShoppingCart\Models\Discount\Discount;

class CartTest extends TestCase
{
    /**
     * @dataProvider totalCostProvider
     */
    public function testCalculateTotalCost(int $quantity, float $price, int $discountPercentage, float $expectedTotal): void
    {
        $product = new Product('test-product-1', 'Product 1', $price);
        $cart = new Cart();
        $cart->addProduct($product, $quantity);

        if ($discountPercentage > 0) {
            $cart->setDiscount(new Discount($discountPercentage));
        }

        $this->assertEquals($expectedTotal, $cart->calculateTotalCost());
    }

    public static function totalCostProvider(): array
    {
        return [
            'No discount, single item' => [1, 10.0, 0, 10.0],
            'No discount, multiple items' => [3, 10.0, 0, 30.0],
            '10% discount' => [2, 15.0, 10, 27.0],
            '50% discount' => [1, 100.0, 50, 50.0],
            '100% discount' => [1, 25.0, 100, 0.0],
        ];
    }

    public function testClearCartEmptiesItemsAndDiscount(): void
    {
        $product = new Product('test-product-1', 'Product 1', 20.0);
        $cart = new Cart();
        $cart->addProduct($product, 2);
        $cart->setDiscount(new Discount(20));

        $cart->clearCart();

        $this->assertEmpty($cart->getCartItems());
        $this->assertNull($cart->getDiscount());
        $this->assertEquals(0.0, $cart->calculateTotalCost());
    }
}
