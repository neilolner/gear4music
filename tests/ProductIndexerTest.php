<?php

declare(strict_types=1);

namespace Tests;

use Gear4music\ArrayRepository;
use Gear4music\Market;
use Gear4music\Product;
use Gear4music\ProductIndexer;
use PHPUnit\Framework\TestCase;

class ProductIndexerTest extends TestCase
{
    public function testHiddenCountryIsExcludedFromThatMarket(): void
    {
        $guitar = new Product(
            sku: 'GUITAR',
            names: ['en' => 'Guitar'],
            prices: ['GBP' => 199.99, 'EUR' => 229.00],
            hiddenCountries: ['FR'],
            weight: 3500,
            boxVolume: 120000,
            stockLevel: 12,
            daysToDeliver: 2,
        );

        $indexer = new ProductIndexer(new ArrayRepository([$guitar]));

        self::assertCount(1, $indexer->getInventory(new Market('GB', 'en', 'GBP')));
        self::assertCount(0, $indexer->getInventory(new Market('FR', 'fr', 'EUR')));
    }

    public function testProductWithNoPriceInThatCurrencyIsExcluded(): void
    {
        $guitar = new Product(
            sku: 'GUITAR',
            names: ['en' => 'Guitar'],
            prices: ['GBP' => 199.99], // no CHF price
            hiddenCountries: [],
            weight: 3500,
            boxVolume: 120000,
            stockLevel: 12,
            daysToDeliver: 2,
        );

        $indexer = new ProductIndexer(new ArrayRepository([$guitar]));

        self::assertCount(0, $indexer->getInventory(new Market('CH', 'it', 'CHF')));
    }

    public function testEachMarketOnlySeesItsOwnPriceAndLanguage(): void
    {
        $guitar = new Product(
            sku: 'GUITAR',
            names: ['en' => 'Guitar', 'de' => 'Guitar de'],
            prices: ['GBP' => 199.99, 'EUR' => 229.00],
            hiddenCountries: [],
            weight: 3500,
            boxVolume: 120000,
            stockLevel: 12,
            daysToDeliver: 2,
        );

        $indexer = new ProductIndexer(new ArrayRepository([$guitar]));

        $gb = $indexer->getInventory(new Market('GB', 'en', 'GBP'))[0];
        $de = $indexer->getInventory(new Market('DE', 'de', 'EUR'))[0];

        self::assertSame('Guitar', $gb->getName());
        self::assertSame(199.99, $gb->getPrice());

        self::assertSame('Guitar de', $de->getName());
        self::assertSame(229.00, $de->getPrice());
    }
}
