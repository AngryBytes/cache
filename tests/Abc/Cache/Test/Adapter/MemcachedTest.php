<?php
/**
 * MemcachedTest.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         Cache
 * @subpackage      Tests
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Abc\Cache\Test\Adapter;

use Abc\Cache\Test\Adapter\TestCase;

use Abc\Cache\Cache;

use Abc\Cache\Adapter\Memcached as MemcachedAdapter;
use Abc\Cache\Adapter\File as FileAdapter;

/**
 * MemcachedTest
 *
 * Testing memcached
 *
 * @category        Abc
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
