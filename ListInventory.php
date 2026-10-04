<?php

/**
 * Lists the inventory each market would see, from sample data.
 *
 *   php list-inventory.php
 */

declare(strict_types=1);

use Gear4music\Market;
use Gear4music\Product;
use Gear4music\ProductIndexer;
use Gear4music\Condition;

require __DIR__ . '/vendor/autoload.php';


$markets = [
    new Market('GB', 'en', 'GBP'),
    new Market('DE', 'de', 'EUR'),
    new Market('FR', 'fr', 'EUR'),
    new Market('CH', 'it', 'CHF'),
    new Market('CH', 'fr', 'CHF'),
];

$indexer = new ProductIndexer([
    new Product(
        sku: 'GUITAR',
        names: ['en' => 'Guitar', 'de' => 'Guitar de', 'fr' => 'Guitar fr'],
        prices: ['GBP' => 199.99, 'EUR' => 229.00],
        hiddenCountries: [],
        weight: 3500,
        boxVolume: 120000,
        stockLevel: 12,
        daysToDeliver: 2,
    ),
    new Product(
        sku: 'KEYBD',
        names: ['en' => 'Piano', 'fr' => 'Piano fr'],
        prices: ['GBP' => 499.95, 'EUR' => 579.00, 'CHF' => 5990.00],
        hiddenCountries: ['DE'],
        weight: 18000,
        boxVolume: 450000,
        stockLevel: 0,
        daysToDeliver: 7,
    ),
    new Product(
        sku: 'DRUMS',
        names: ['en' => 'Drum Kit', 'de' => 'Drum Kit de'],
        prices: ['GBP' => 399.00, 'EUR' => 459.00],
        hiddenCountries: ['FR'],
        weight: 22000,
        boxVolume: 600000,
        stockLevel: 3,
        daysToDeliver: 4,
    ),
    new Product(
        sku: 'GUITAR-USED',
        names: ['en' => 'Guitar', 'de' => 'Guitar de', 'fr' => 'Guitar fr'],
        prices: ['GBP' => 129.00, 'EUR' => 149.00],
        hiddenCountries: [],
        weight: 3500,
        boxVolume: 120000,
        stockLevel: 1,
        daysToDeliver: 2,
        condition: Condition::Used,
    ),
    new Product(
        sku: 'KEYBD-REFURB',
        names: ['en' => 'Piano', 'fr' => 'Piano fr'],
        prices: ['GBP' => 399.00, 'EUR' => 459.00],
        hiddenCountries: ['DE'],
        weight: 18000,
        boxVolume: 450000,
        stockLevel: 0,
        daysToDeliver: 3,
        condition: Condition::Refurbished,
    ),
    new Product(
        sku: 'SW1',
        names: ['en' => 'Studio software', 'de' => 'Studio software de'],
        prices: ['GBP' => 99.00, 'EUR' => 119.00, 'SEK' => 1190.00],
        hiddenCountries: ['FR'], // licence doesn't cover FR
        weight: 0,
        boxVolume: 0,
        stockLevel: 0, // not tracked for downloads
        daysToDeliver: 0,
        isDigital: true,
    ),
]);


foreach ($markets as $market) {
    echo "{$market->country}, {$market->language}, {$market->currency}\n";

    $inventory = $indexer->getInventory($market);
    if ($inventory === []) {
        echo "  (no current stock)\n";
    }

    foreach ($inventory as $item) {
        $days = $item->getDaysToDeliver();
        printf(
            "  %-12s %-20s %-11s %10s %s  %s\n",
            $item->getSku(),
            $item->getName(),
            $item->isDigital() ? 'digital' : $item->getCondition()->value,
            number_format($item->getPrice(), 2), // assume 2 dec.pl
            $item->getCurrency(),
            match (true) {
                $item->isDigital() => 'instant download',
                $days === null => 'out of stock, no restock date',
                $item->isAvailable() => "{$item->getStockLevel()} in stock, {$days} days",
                default => "out of stock, {$days} days",
            },
        );
    }
    echo "\n";
}
