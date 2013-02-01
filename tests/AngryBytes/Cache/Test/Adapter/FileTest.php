<?php
/**
 * FileTest.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Tests
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache\Test\Adapter;

use AngryBytes\Cache\Test\Adapter\TestCase;

use AngryBytes\Cache\Cache;
use AngryBytes\Cache\Adapter\File as FileAdapter;

/**
 * FileTest
 *
 * Testing file adapter
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Tests
 */
class FileTest extends TestCase
{
    /**
     * Get the cache
     *
     * @return Cache
     **/
    protected function getCache()
    {
        $dir = realpath(__DIR__ . '/../../../../tmp');

        $adapter = new FileAdapter($dir);

        return new Cache($adapter);
    }
}

