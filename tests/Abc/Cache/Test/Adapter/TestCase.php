<?php
/**
 * TestCase.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         Cache
 * @subpackage      Tests
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Abc\Cache\Test\Adapter;

use Abc\Cache\Cache;

use \PHPUnit_Framework_TestCase as PUTestCase;

/**
 * TestCase
 *
 * Testing memcached
 *
 * @category        Abc
 * @package         Cache
 * @subpackage      Tests
 */
class TestCase extends PUTestCase
{
    /**
     * Cache
     *
     * @var string
     **/
    private $cache;

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
     * @return TestCase
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
     * Test saving with prefix
     *
     * @return void
     **/
    public function testSaveSimplePrefixed()
    {
        // Set a prefix and save/load some items
        $this->getCache()->setIdPrefix('foo');
        $this->assertSaveAndLoad('foo', 'string');
        $this->assertSaveAndLoad(123, 'int');
        $this->assertSaveAndLoad(123.456, 'float');
        $this->assertSaveAndLoad(array(1, 2, 'foo', 'bar'), 'array');

        // Change prefix after save
        $this->assertSaveAndLoad('bar', 'foo');
        $this->getCache()->addIdPrefix('bar');
        $this->assertInstanceOf(
            'Abc\Cache\ResultNotFound',
            $this->getCache()->load('foo'),
            'After prefix change items should not load'
        );
    }

    /**
     * Test saving an object
     *
     * @return void
     **/
    public function testSaveObject()
    {
        // stdClass object
        $obj = new \stdClass;
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
            $this->getCache()->delete($key);

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
     * @return TestCase
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
