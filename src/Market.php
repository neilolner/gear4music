<?php

declare(strict_types=1);

namespace Gear4music;

/**
 * Country, language and currency combination. Kept separate to allow multiple values for each
 */
class Market
{
    public function __construct(
        public string $country,  // eg. "DE"
        public string $language, // eg. "de"
        public string $currency, // eg. "EUR"
    ) {
    }
}
