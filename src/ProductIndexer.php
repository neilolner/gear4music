<?php

declare(strict_types=1);

namespace Gear4music;

class ProductIndexer
{
    /**
     * @param list<Product> $products stand-in for the database; inject a repository in real code
     */
    public function __construct(private readonly array $products)
    {
    }

    /**
     * @return list<InventoryInterface> the products visible in the market, localised
     */
    public function getInventory(Market $market): array
    {
        $inventory = [];
        foreach ($this->products as $product) {
            if ($product->isVisibleIn($market)) {
                $inventory[] = $product->forMarket($market);
            }
        }

        return $inventory;
    }
}
