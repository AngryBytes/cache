<?php
/**
 * Memcached.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Adapter
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache\Adapter;

use AngryBytes\Cache\Adapter;
use AngryBytes\Cache\ResultNotFound;

use \Exception as Exception;

use \Memcached as MemcachedAdapter;

/**
 * Memcached
 *
 * Simple memcached adapter for AngryBytes\Cache.
 *
 * Does not use any of the more advanced functions such as CAS and deferred
 * loading. Allows multiple servers to be added through addServer().
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Adapter
 */
class Memcached extends Adapter
{
    /**
     * Memcached adapter
     *
     * @var MemcachedAdapter
     **/
    private $memcached;

    /**
     * Constructor
     *
     * @return void
     **/
    public function __construct()
    {
        // Sanity check
        if (class_exists('MemcachedAdapter')) {
            throw new Exception('Memcached extension not loaded');
        }

        // Init the adapter
        $this->setMemcached(
            new MemcachedAdapter
        );
    }

    /**
     * Get the memcached adapter
     *
     * @return MemcachedAdapter
     */
    public function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * Set the memcached adapter
     *
     * @param  MemcachedAdapter $memcached
     * @return Memcached
     */
    public function setMemcached(MemcachedAdapter $memcached)
    {
        $this->memcached = $memcached;

        return $this;
    }

    /**
     * Add a server
     *
     * @param  string    $host
     * @param  int       $port
     * @param  int       $weight
     * @return Memcached
     **/
    public function addServer($host, $port, $weight = 0)
    {
        $this->getMemcached()->addServer($host, $port, $weight);

        return $this;
    }

    /**
     * Save some data
     *
     * @param  string $data
     * @param  string $id
     * @param  int    $lifeTime lifetime in seconds
     * @return bool
     **/
    public function save($data, $id, $lifeTime)
    {
        return $this->getMemcached()->set($id, $data, $lifeTime);
    }

    /**
     * Load an item from the cache
     *
     * @param  string               $id
     * @return mixed|ResultNotFound
     **/
    public function load($id)
    {
        $result = $this->getMemcached()->get($id);

        if ($this->getMemcached()->getResultCode() !== MemcachedAdapter::RES_SUCCESS) {
            return new ResultNotFound($id);
        }

        return $result;
    }

    /**
     * Delete an item from the cache
     *
     * @param  string $id
     * @return bool
     **/
    public function delete($id)
    {
        return $this->getMemcached()->delete($id);
    }
}

