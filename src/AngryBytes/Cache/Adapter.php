<?php
/**
 * Adapter.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache;

use AngryBytes\Cache\ResultNotFound;

/**
 * Adapter
 *
 * Cache backend adapter
 *
 * @category        AngryBytes
 * @package         Cache
 */
abstract class Adapter
{
    /**
     * Save some data
     *
     * @param  string $data
     * @param  string $id
     * @param  int    $lifeTime lifetime in seconds
     * @return bool
     **/
    abstract public function save($data, $id, $lifeTime);

    /**
     * Load an item from the cache
     *
     * @param  string               $id
     * @return mixed|ResultNotFound
     **/
    abstract public function load($id);

    /**
     * Delete an item from the cache
     *
     * @param  string $id
     * @return bool
     **/
    abstract public function delete($id);
}
