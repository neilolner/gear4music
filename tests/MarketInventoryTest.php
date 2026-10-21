<?php

declare(strict_types=1);

namespace Tests;

use Gear4music\Condition;
use Gear4music\Market;
use Gear4music\Product;
use PHPUnit\Framework\TestCase;

class MarketInventoryTest extends TestCase
{
    private function market(): Market
    {
        return new Market('GB', 'en', 'GBP');
    }

    public function testPhysicalNewProductInStock(): void
    {
        $product = new Product(
            sku: 'GUITAR',
            names: ['en' => 'Guitar'],
            prices: ['GBP' => 199.99],
            hiddenCountries: [],
            weight: 3500,
            boxVolume: 120000,
            stockLevel: 12,
            daysToDeliver: 2,
        );

        $inventory = $product->forMarket($this->market());

        self::assertTrue($inventory->isAvailable());
        self::assertSame(3500, $inventory->getWeight());
        self::assertSame(120000, $inventory->getBoxVolume());
        self::assertSame(2, $inventory->getDaysToDeliver());
    }

    public function testSoldOutUsedItemHasNoRestockDate(): void
    {
        $product = new Product(
            sku: 'GUITAR-USED',
            names: ['en' => 'Guitar'],
            prices: ['GBP' => 129.00],
            hiddenCountries: [],
            weight: 3500,
            boxVolume: 120000,
            stockLevel: 0,
            daysToDeliver: 2,
            condition: Condition::Used,
        );

        $inventory = $product->forMarket($this->market());

        self::assertFalse($inventory->isAvailable());
        self::assertNull($inventory->getDaysToDeliver());
    }

    public function testSoldOutNewItemStillHasARestockDate(): void
    {
        $product = new Product(
            sku: 'KEYBD',
            names: ['en' => 'Piano'],
            prices: ['GBP' => 499.95],
            hiddenCountries: [],
            weight: 18000,
            boxVolume: 450000,
            stockLevel: 0,
            daysToDeliver: 7,
            // condition defaults to New
        );

        $inventory = $product->forMarket($this->market());

        self::assertFalse($inventory->isAvailable());
        self::assertSame(7, $inventory->getDaysToDeliver());
    }

    public function testDigitalProductIsAlwaysAvailableAndWeightless(): void
    {
        $product = new Product(
            sku: 'SW1',
            names: ['en' => 'Studio software'],
            prices: ['GBP' => 99.00],
            hiddenCountries: [],
            weight: 0,
            boxVolume: 0,
            stockLevel: 0,
            daysToDeliver: 0,
            isDigital: true,
        );

        $inventory = $product->forMarket($this->market());

        self::assertTrue($inventory->isAvailable());
        self::assertTrue($inventory->isDigital());
        self::assertSame(0, $inventory->getWeight());
        self::assertSame(0, $inventory->getBoxVolume());
        self::assertSame(0, $inventory->getDaysToDeliver());
    }
}
