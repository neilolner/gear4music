<?php

declare(strict_types=1);

namespace Gear4music;

use PDO;
use RuntimeException;

class SqliteRepository implements RepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(): array
    {
        $names = [];
        /** @var list<array{product_id: int, language: string, name: string}> $rows */
        $rows = $this->fetchAll('SELECT product_id, language, name FROM product_names');
        foreach ($rows as $row) {
            $names[$row['product_id']][$row['language']] = $row['name'];
        }

        $prices = [];
        /** @var list<array{product_id: int, currency: string, price: float}> $rows */
        $rows = $this->fetchAll('SELECT product_id, currency, price FROM product_prices');
        foreach ($rows as $row) {
            $prices[$row['product_id']][$row['currency']] = $row['price'];
        }

        $hiddenCountries = [];
        /** @var list<array{product_id: int, country: string}> $rows */
        $rows = $this->fetchAll('SELECT product_id, country FROM product_visibilities');
        foreach ($rows as $row) {
            $hiddenCountries[$row['product_id']][] = $row['country'];
        }

        $products = [];
        /** @var list<array{id: int, sku: string, weight: int, box_volume: int, stock_level: int,
         *      days_to_deliver: int, condition: string, is_digital: int}> $rows */
        $rows = $this->fetchAll('SELECT * FROM products ORDER BY id');
        foreach ($rows as $row) {
            $products[] = new Product(
                sku: $row['sku'],
                names: $names[$row['id']] ?? [],
                prices: $prices[$row['id']] ?? [],
                hiddenCountries: $hiddenCountries[$row['id']] ?? [],
                weight: $row['weight'],
                boxVolume: $row['box_volume'],
                stockLevel: $row['stock_level'],
                daysToDeliver: $row['days_to_deliver'],
                condition: Condition::from($row['condition']),
                isDigital: (bool) $row['is_digital'],
            );
        }

        return $products;
    }

    /**
     * @return array<mixed>
     */
    private function fetchAll(string $sql): array
    {
        $statement = $this->pdo->query($sql);
        if ($statement === false) {
            throw new RuntimeException("Query failed: {$sql}");
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
