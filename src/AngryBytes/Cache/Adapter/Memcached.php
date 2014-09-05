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

use AngryBytes\Cache\Adapter\Memcached\AdapterInterface as MemcachedAdapterInterface;
use AngryBytes\Cache\Adapter\Memcached\Adapter as MemcachedAdapter;

use \Exception as Exception;


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
     * @var MemcachedAdapterInterface
     **/
    private $memcached;

    /**
     * Constructor
     *
     * @param  mixed $persistentId By default the Memcached instances are destroyed at the end of the request.
     *                             To create an instance that persists between requests, use $persistentId to
     *                             specify a unique ID for the instance. All instances created with the same
     *                             $persistentId will share the same connection.
     * @return void
     **/
    public function __construct($persistentId = null)
    {
        // Init the adapter
        $this->setMemcached(
            new MemcachedAdapter($persistentId)
        );
    }

    /**
     * Get the memcached adapter
     *
     * @return MemcachedAdapterInterface
     */
    public function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * Set the memcached adapter
     *
     * @param  MemcachedAdapterInterface $memcached
     * @return Memcached
     */
    public function setMemcached(MemcachedAdapterInterface $memcached)
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

