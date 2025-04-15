<?php

declare(strict_types=1);

namespace Lukas\KoskampShoppingCart\Models\Product;

class Product
{
    public function __construct(
        private string $productNumber,
        private string $description,
        private float $price,
    )
    {
    }

    public function getProductNumber(): string
    {
        return $this->productNumber;
    }

    public function setProductNumber(string $productNumber): self
    {
        $this->productNumber = $productNumber;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
