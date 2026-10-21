<?php

declare(strict_types=1);

namespace Gear4music;

class ProductIndexer
{
    public function __construct(private readonly RepositoryInterface $repository)
    {
    }

    /**
     * @return list<InventoryInterface> the products visible in the market, localised
     */
    public function getInventory(Market $market): array
    {
        $inventory = [];
        foreach ($this->repository->all() as $product) {
            if ($product->isVisibleIn($market)) {
                $inventory[] = $product->forMarket($market);
            }
        }

        return $inventory;
    }
}
