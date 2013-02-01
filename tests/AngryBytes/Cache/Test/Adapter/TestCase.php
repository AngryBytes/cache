<?php
/**
 * TestCase.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Tests
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache\Test\Adapter;

use AngryBytes\Cache\Cache;

use \PHPUnit_Framework_TestCase as PUTestCase;

/**
 * TestCase
 *
 * Testing memcached
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Tests
 */
abstract class TestCase extends PUTestCase
{
    /**
     * Get the cache
     *
     * @return Cache
     */
    abstract protected function getCache();

    /**
     * Test saving
     *
     * @return void
     **/
    public function testSaveSimple()
    {
        $cache = $this->getCache();

        $this->assertSaveAndLoad($cache, 'foo', 'string');
        $this->assertSaveAndLoad($cache, 123, 'int');
        $this->assertSaveAndLoad($cache, 123.456, 'float');
        $this->assertSaveAndLoad($cache, array(1, 2, 'foo', 'bar'), 'array');
    }

    /**
     * Test saving with prefix
     *
     * @return void
     **/
    public function testSaveSimplePrefixed()
    {
        $cache = $this->getCache();

        // Set a prefix and save/load some items
        $cache->setIdPrefix('foo');
        $this->assertSaveAndLoad($cache, 'foo', 'string');
        $this->assertSaveAndLoad($cache, 123, 'int');
        $this->assertSaveAndLoad($cache, 123.456, 'float');
        $this->assertSaveAndLoad($cache, array(1, 2, 'foo', 'bar'), 'array');

        // Change prefix after save
        $this->assertSaveAndLoad($cache, 'bar', 'foo');
        $cache->addIdPrefix('bar');
        $this->assertInstanceOf(
            'AngryBytes\Cache\ResultNotFound',
            $cache->load('foo'),
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
        $cache = $this->getCache();

        // stdClass object
        $obj = new \stdClass;
        $obj->foo = 'foo';
        $obj->bar = 'bar';

        $this->assertSaveAndLoad($cache, $obj, 'object');

        // Class
        $this->assertSaveAndLoad($cache, new TestSerialize, 'serialize');
    }

    /**
     * Test deletion
     *
     * @return void
     **/
    public function testDelete()
    {
        $cache = $this->getCache();

        // Save and delete a couple of items
        foreach(array('delete1', 'delete2', 'delete3', 'delete4') as $key) {

            // Save (And make sure it's there)
            $this->assertSaveAndLoad($cache, 'foo-'  . $key , $key);

            // Delete
            $cache->delete($key);

            $this->assertInstanceOf(
                'AngryBytes\Cache\ResultNotFound',
                $cache->load($key),
                'Result for key "' . $key . '" should not load after delete'
            );
        }
    }

    /**
     * Assert saving and loading of data
     *
     * @param  Cache         $cache
     * @param  mixed         $data
     * @param  string        $key
     * @return TestCase
     **/
    private function assertSaveAndLoad(Cache $cache, $data, $key)
    {
        $saved = $cache->save($data, $key);

        $this->assertTrue(
            $saved,
            'Adapter should save for key "' . $key . '"'
        );

        $loaded = $cache->load($key);

        $this->assertEquals(
            $loaded,
            $data,
            'Adapter should load correct data for "' . $key . '"'
        );

        return $this;
    }
}
