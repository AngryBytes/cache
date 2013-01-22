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
     * Cache
     *
     * @var string
     **/
    private $cache;

    /**
     * Constructor
     *
     * @return void
     **/
    public function __construct()
    {
        $adapter = new MemcachedAdapter;
        $adapter->addServer('aberforth', 11211);

        $this->setCache(
            new Cache($adapter)
        );
    }
}
