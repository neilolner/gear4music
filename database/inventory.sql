-- Schema and seed data matching ListInventory.php

CREATE TABLE products (
    id INTEGER PRIMARY KEY,
    sku TEXT NOT NULL UNIQUE,
    weight INTEGER NOT NULL,
    box_volume INTEGER NOT NULL,
    stock_level INTEGER NOT NULL,
    days_to_deliver INTEGER NOT NULL,
    condition TEXT NOT NULL DEFAULT 'new',
    is_digital INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE product_names (
    product_id INTEGER NOT NULL REFERENCES products (id),
    language TEXT NOT NULL,
    name TEXT NOT NULL,
    PRIMARY KEY (product_id, language)
);

CREATE TABLE product_prices (
    product_id INTEGER NOT NULL REFERENCES products (id),
    currency TEXT NOT NULL,
    price REAL NOT NULL,
    PRIMARY KEY (product_id, currency)
);

-- Countries the product is hidden in
CREATE TABLE product_visibilities (
    product_id INTEGER NOT NULL REFERENCES products (id),
    country TEXT NOT NULL,
    PRIMARY KEY (product_id, country)
);

INSERT INTO products (id, sku, weight, box_volume, stock_level, days_to_deliver, condition, is_digital) VALUES
    (1, 'GUITAR', 3500, 120000, 12, 2, 'new', 0),
    (2, 'KEYBD', 18000, 450000, 0, 7, 'new', 0),
    (3, 'DRUMS', 22000, 600000, 3, 4, 'new', 0),
    (4, 'GUITAR-USED', 3500, 120000, 1, 2, 'used', 0),
    (5, 'KEYBD-REFURB', 18000, 450000, 0, 3, 'refurbished', 0),
    (6, 'SW1', 0, 0, 0, 0, 'new', 1);

INSERT INTO product_names (product_id, language, name) VALUES
    (1, 'en', 'Guitar'), (1, 'de', 'Guitar de'), (1, 'fr', 'Guitar fr'),
    (2, 'en', 'Piano'), (2, 'fr', 'Piano fr'),
    (3, 'en', 'Drum Kit'), (3, 'de', 'Drum Kit de'),
    (4, 'en', 'Guitar'), (4, 'de', 'Guitar de'), (4, 'fr', 'Guitar fr'),
    (5, 'en', 'Piano'), (5, 'fr', 'Piano fr'),
    (6, 'en', 'Studio software'), (6, 'de', 'Studio software de');

INSERT INTO product_prices (product_id, currency, price) VALUES
    (1, 'GBP', 199.99), (1, 'EUR', 229.00),
    (2, 'GBP', 499.95), (2, 'EUR', 579.00), (2, 'CHF', 5990.00),
    (3, 'GBP', 399.00), (3, 'EUR', 459.00),
    (4, 'GBP', 129.00), (4, 'EUR', 149.00),
    (5, 'GBP', 399.00), (5, 'EUR', 459.00),
    (6, 'GBP', 99.00), (6, 'EUR', 119.00), (6, 'SEK', 1190.00);

INSERT INTO product_visibilities (product_id, country) VALUES
    (2, 'DE'),
    (3, 'FR'),
    (5, 'DE'),
    (6, 'FR');
