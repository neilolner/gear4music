<?php

declare(strict_types=1);

namespace Tests;

use Gear4music\Condition;
use Gear4music\Market;
use Gear4music\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private function guitar(): Product
    {
        return new Product(
            sku: 'GUITAR',
            names: ['en' => 'Guitar', 'de' => 'Guitar de'],
            prices: ['GBP' => 199.99, 'EUR' => 229.00],
            hiddenCountries: ['FR'],
            weight: 3500,
            boxVolume: 120000,
            stockLevel: 12,
            daysToDeliver: 2,
        );
    }

    public function testVisibleWhenCountryNotHiddenAndCurrencyPriced(): void
    {
        $market = new Market('GB', 'en', 'GBP');

        self::assertTrue($this->guitar()->isVisibleIn($market));
    }

    public function testNotVisibleInAHiddenCountry(): void
    {
        $market = new Market('FR', 'fr', 'EUR');

        self::assertFalse($this->guitar()->isVisibleIn($market));
    }

    public function testNotVisibleWhenNoPriceInThatCurrency(): void
    {
        $market = new Market('CH', 'it', 'CHF');

        self::assertFalse($this->guitar()->isVisibleIn($market));
    }

    public function testForMarketLocalisesNameAndPrice(): void
    {
        $inventory = $this->guitar()->forMarket(new Market('DE', 'de', 'EUR'));

        self::assertSame('Guitar de', $inventory->getName());
        self::assertSame(229.00, $inventory->getPrice());
        self::assertSame('EUR', $inventory->getCurrency());
    }

    public function testForMarketFallsBackToEnglishWhenTranslationMissing(): void
    {
        // No Italian name was supplied for the guitar.
        $inventory = $this->guitar()->forMarket(new Market('IT', 'it', 'EUR'));

        self::assertSame('Guitar', $inventory->getName());
    }

    public function testDefaultConditionIsNew(): void
    {
        self::assertSame(Condition::New, $this->guitar()->condition);
    }
}
