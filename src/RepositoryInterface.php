<?php

declare(strict_types=1);

namespace Gear4music;

interface RepositoryInterface
{
    /**
     * @return list<Product>
     */
    public function all(): array;
}
