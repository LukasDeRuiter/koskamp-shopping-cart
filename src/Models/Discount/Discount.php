<?php

declare(strict_types=1);

namespace Lukas\KoskampShoppingCart\Models\Discount;

class Discount
{
    public function __construct(private float $percentage = 0)
    {
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function apply(float $total): float
    {
        return $total - (($total / 100)  * $this->getPercentage());
    }
}
