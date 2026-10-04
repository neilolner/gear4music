<?php

declare(strict_types=1);

namespace Gear4music;

/**
 * A Product for one Market - replaces SimpleInventory.
 */
class MarketInventory implements InventoryInterface
{
    public function __construct(
        private Product $product,
        private string $name,
        private float $price,
        private string $currency,
    ) {
    }

    public function getSku(): string
    {
        return $this->product->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function isAvailable(): bool
    {
        return $this->product->stockLevel > 0;
    }

    public function getWeight(): int
    {
        return $this->product->weight;
    }

    public function getBoxVolume(): int
    {
        return $this->product->boxVolume;
    }

    public function getDaysToDeliver(): int
    {
        return $this->product->daysToDeliver;
    }

    public function getStockLevel(): int
    {
        return $this->product->stockLevel;
    }
}
