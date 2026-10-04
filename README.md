# Gear4music POC

Install and run

```bash
composer install
composer check             # code style (PSR-12) + PHPStan (level max)
composer cs-fix            # fix code style
php ListInventory.php
```

Notes

1. MarketInventory replaces SimpleInventory - 1 product for 1 market
2. Market has country, language and currency allowing countries with multiple languages and multiple countries the same currency etc
3. ProductIndexer uses functions in Product to show visible products in each market and localise them
4. ListInventory sets up data and for each market iterates over its inventory using the indexer
5. No translation for an item falls back to english
6. Price fields left as float but best as int for calculations later
7. Visibility is just a list of countries that don't stock each item - could be White/Black list etc
8. Digital products and condition added as fields on Product - new Product for each condition (maybe ProductVariant later?). Simple approach does duplicate data
9. Digital products never out of stock. Used or refurbished shown as no restock date once sold
10. Tests/TDD. Initially based on data in ListInventory
