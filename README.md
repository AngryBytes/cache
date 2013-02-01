# ABC Cache

[![Build Status](https://travis-ci.org/AngryBytes/cache.png?branch=master)](https://travis-ci.org/AngryBytes/cache)

This is a simple cache store with support for a variety of backends. A file and
memcached backend are included.


## Installation

Installation through [Composer at Packagist](https://packagist.org/packages/angrybytes/abc-cache)

## Usage

Usage is simple:

```php
<?php

// Instantiate
$adapter = new Abc\Cache\Adapter\Memcached;
$adapter->addServer('localhost', 11211);

$cache = new Abc\Cache\Cache($adapter);

// Save
$cache->save($yourExpensiveData, 'cache-key');

// Load
$data = $cache->load('cache-key');

// Delete
$data = $cache->delete('cache-key');
```

### Result checking

There is a special return type `Abc\Cache\ResultNotFound` that signifies the
result can not be retrieved:

```php
<?php

// Load
$data = $cache->load('cache-key');

// Check
if ($data instanceof Abc\Cache\ResultNotFound) {
    $yourExpensiveData = yourExpensiveMethod();

    // Save
    $cache->save($yourExpensiveData, 'cache-key');
}
```

### ID Prefixing

If you need to support more than one cache store on the same backend you can
add a prefix for all id's:

```php
<?php

// Two stores with same adapter but different prefix:

$cache1 = new Abc\Cache\Cache($adapter);
$cache1->setIdPrefix('foo');

$cache2 = new Abc\Cache\Cache($adapter);
$cache2->setIdPrefix('foo');
```

You can also add more than one prefix, which can be handy for key cleaning:

```php
<?php

$cache = new Abc\Cache\Cache($adapter);
$cache->addIdPrefix('foo');
$cache->addIdPrefix('bar');
```


