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

use Abc\Cache\Cache;
use Abc\Cache\Adapter\Memcached as MemcachedAdapter;

use \PHPUnit_Framework_TestCase as TestCase;

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

    /**
     * Get the cache
     *
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set the cache
     *
     * @param  Cache         $cache
     * @return MemcachedTest
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Test saving
     *
     * @return void
     **/
    public function testSaveSimple()
    {
        $this->assertSaveAndLoad('foo', 'string');
        $this->assertSaveAndLoad(123, 'int');
        $this->assertSaveAndLoad(123.456, 'float');
        $this->assertSaveAndLoad(array(1, 2, 'foo', 'bar'), 'array');
    }

    /**
     * Test saving an object
     *
     * @return void
     **/
    public function testSaveObject()
    {
        // stdClass object
        $obj = new stdClass;
        $obj->foo = 'foo';
        $obj->bar = 'bar';

        $this->assertSaveAndLoad($obj, 'object');

        // Class
        $this->assertSaveAndLoad(new TestSerialize, 'serialize');
    }

    /**
     * Test deletion
     *
     * @return void
     **/
    public function testDelete()
    {
        // Save and delete a couple of items
        foreach(array('delete1', 'delete2', 'delete3', 'delete4') as $key) {

            // Save (And make sure it's there)
            $this->assertSaveAndLoad('foo-'  . $key , $key);

            // Delete
            $this->getcache()->delete($key);

            $this->assertInstanceOf(
                'Abc\Cache\ResultNotFound',
                $this->getCache()->load($key),
                'Result for key "' . $key . '" should not load after delete'
            );
        }
    }

    /**
     * Assert saving and loading of data
     *
     *
     * @param  mixed         $data
     * @param  string        $key
     * @return MemcachedTest
     **/
    private function assertSaveAndLoad($data, $key)
    {
        $saved = $this->getCache()->save($data, $key);

        $this->assertTrue(
            $saved,
            'Adapter should save for key "' . $key . '"'
        );

        $loaded = $this->getCache()->load($key);

        $this->assertEquals(
            $loaded,
            $data,
            'Adapter should load correct data for "' . $key . '"'
        );

        return $this;
    }
}

/**
 * TestSerialize
 *
 * Class for testing serialization of objects with class
**/
class TestSerialize
{
    public $foo = 'foo';
    public $bar = 'bar';
    public $baz = 'baz';
}
