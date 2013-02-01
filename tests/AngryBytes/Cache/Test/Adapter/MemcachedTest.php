<?php
/**
 * MemcachedTest.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Tests
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache\Test\Adapter;

use AngryBytes\Cache\Test\Adapter\TestCase;

use AngryBytes\Cache\Cache;

use AngryBytes\Cache\Adapter\Memcached as MemcachedAdapter;
use AngryBytes\Cache\Adapter\File as FileAdapter;

/**
 * MemcachedTest
 *
 * Testing memcached
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Tests
 */
class MemcachedTest extends TestCase
{
    /**
     * Get the cache
     *
     * @return Cache
     **/
    protected function getCache()
    {
        $adapter = new MemcachedAdapter;
        $adapter->addServer('aberforth', 11211);

        return new Cache($adapter);
    }
}
