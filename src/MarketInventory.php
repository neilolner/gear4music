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
        return $this->product->isDigital || $this->product->stockLevel > 0;
    }

    public function getWeight(): int
    {
        return $this->product->isDigital ? 0 : $this->product->weight;
    }

    public function getBoxVolume(): int
    {
        return $this->product->isDigital ? 0 : $this->product->boxVolume;
    }

    public function getDaysToDeliver(): ?int
    {
        if ($this->product->isDigital) {
            return 0;
        }

        // New stock can be reordered; a sold used or refurbished item has no restock date
        if ($this->product->stockLevel === 0 && $this->product->condition !== Condition::New) {
            return null;
        }

        return $this->product->daysToDeliver;
    }

    public function getStockLevel(): int
    {
        return $this->product->stockLevel;
    }

    public function getCondition(): Condition
    {
        return $this->product->condition;
    }

    public function isDigital(): bool
    {
        return $this->product->isDigital;
    }
}
