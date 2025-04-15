<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Lukas\KoskampShoppingCart\Models\Discount\Discount;

class DiscountTest extends TestCase
{
    /**
     * @param float $percentage
     * @param float $originalPrice
     * @param float $expectedPrice
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testApplyDiscount(float $percentage, float $originalPrice, float $expectedPrice): void
    {
        $discount = new Discount($percentage);
        $this->assertEquals($expectedPrice, $discount->apply($originalPrice));
    }

    public static function dataProvider(): array
    {
        return [
            '0% discount' => [0, 100.0, 100.0],
            '10% discount' => [10, 100.0, 90.0],
            '50% discount' => [50, 100.0, 50.0],
            '100% discount' => [100, 100.0, 0.0],
        ];
    }
}
