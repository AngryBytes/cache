<?php
/**
 * FileTest.php
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
use Abc\Cache\Adapter\File as FileAdapter;

/**
 * FileTest
 *
 * Testing file adapter
 *
 * @category        Abc
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
        $adapter = new FileAdapter('/Users/naneau/tmp');

        return new Cache($adapter);
    }
}

