<?php

declare(strict_types=1);

namespace Gear4music;

/**
 * Inventory item in a Market.
 *
 * getCurrency() added.
 */
interface InventoryInterface
{
    public function getSku(): string;
    public function getName(): string;
    public function getPrice(): float;
    public function getCurrency(): string;
    public function isAvailable(): bool;
    public function getWeight(): int;
    public function getBoxVolume(): int;
    public function getDaysToDeliver(): int;
    public function getStockLevel(): int;
}
