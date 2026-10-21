<?php

declare(strict_types=1);

namespace Gear4music;

/**
 * Prints the inventory each market would see - shared by ListInventory and ListInventoryDb.
 */
class InventoryPrinter
{
    public function __construct(private readonly ProductIndexer $indexer)
    {
    }

    /**
     * @param list<Market> $markets
     */
    public function print(array $markets): void
    {
        foreach ($markets as $market) {
            echo "{$market->country}, {$market->language}, {$market->currency}\n";

            $inventory = $this->indexer->getInventory($market);
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
    }
}
