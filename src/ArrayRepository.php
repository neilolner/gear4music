<?php

declare(strict_types=1);

namespace Gear4music;

class ArrayRepository implements RepositoryInterface
{
    /**
     * @param list<Product> $products
     */
    public function __construct(private readonly array $products)
    {
    }

    public function all(): array
    {
        return $this->products;
    }
}
