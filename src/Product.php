<?php

declare(strict_types=1);

namespace Gear4music;

/**
 * Product data as it would come from the database (via joins) inc translations, currencies and
 * the countries it may not be sold in.
 *
 * 2022: each condition is a Product
 */
class Product
{
    public const DEFAULT_LANGUAGE = 'en';

    /**
     * @param array<string, string> $names          language => name; inc DEFAULT_LANGUAGE
     * @param array<string, float>  $prices         currency => price
     * @param list<string>          $hiddenCountries countries this product unavailable
     */
    public function __construct(
        public string $sku,
        public array $names,
        public array $prices,
        public array $hiddenCountries,
        public int $weight,
        public int $boxVolume,
        public int $stockLevel,
        public int $daysToDeliver,
        public Condition $condition = Condition::New,
        public bool $isDigital = false,
    ) {
    }

    public function isVisibleIn(Market $market): bool
    {
        return !in_array($market->country, $this->hiddenCountries, true)
            && isset($this->prices[$market->currency]);
    }

    public function forMarket(Market $market): MarketInventory
    {
        return new MarketInventory(
            $this,
            $this->names[$market->language] ?? $this->names[self::DEFAULT_LANGUAGE],
            $this->prices[$market->currency],
            $market->currency,
        );
    }
}
