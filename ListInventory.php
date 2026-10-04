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
]);


foreach ($markets as $market) {
    echo "{$market->country}, {$market->language}, {$market->currency}\n";

    $inventory = $indexer->getInventory($market);
    if ($inventory === []) {
        echo "  (no current stock)\n";
    }

    foreach ($inventory as $item) {
        printf(
            "  %-9s %-20s %10s %s  %s, %d days\n",
            $item->getSku(),
            $item->getName(),
            number_format($item->getPrice(), 2), // assume 2 dec.pl
            $item->getCurrency(),
            $item->isAvailable() ? "{$item->getStockLevel()} in stock" : 'out of stock',
            $item->getDaysToDeliver(),
        );
    }
    echo "\n";
}
