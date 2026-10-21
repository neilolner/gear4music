<?php

/**
 * Lists the inventory each market would see, from the SQLite database.
 *
 *   composer db   # (re)create database/inventory.sqlite
 *   php ListInventoryDb.php
 */

declare(strict_types=1);

use Gear4music\InventoryPrinter;
use Gear4music\Market;
use Gear4music\ProductIndexer;
use Gear4music\SqliteRepository;

require __DIR__ . '/vendor/autoload.php';


$markets = [
    new Market('GB', 'en', 'GBP'),
    new Market('DE', 'de', 'EUR'),
    new Market('FR', 'fr', 'EUR'),
    new Market('CH', 'it', 'CHF'),
    new Market('CH', 'fr', 'CHF'),
];

$repository = new SqliteRepository(new PDO('sqlite:' . __DIR__ . '/database/inventory.sqlite'));

(new InventoryPrinter(new ProductIndexer($repository)))->print($markets);
