<?php
/**
 * Memcached.php
 *
 * ABC Manager 5
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Test
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache\Test\Adapter\Mock;

use AngryBytes\Cache\Adapter\Memcached\AdapterInterface as MemcachedAdapterInterface;

use \Memcached as MemcachedAdapter;

/**
 * Memcached
 *
 * Mock memcached adapter
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Test
 */
class Memcached implements MemcachedAdapterInterface
{
    private $data = array();

    private $resultCode;

    /**
     * no-op
     *
     * @return Memcached
     **/
    public function addServer($host, $port, $weight = 0)
    {
        return $this;
    }

    /**
     * Set an item
     *
     * @param string $id
     * @param string $data
     * @param int $lifetime
     * @return bool
     **/
    public function set($id, $data, $lifeTime = 0)
    {
        $this->data[$id] = $data;

        return true;
    }

    /**
     * get an item
     *
     * @param string $id
     * @return mixed
     **/
    public function get($id)
    {
        if (isset($this->data[$id])) {
            $this->setResultCode(MemcachedAdapter::RES_SUCCESS);

            return $this->data[$id];
        }

        $this->setResultCode(MemcachedAdapter::RES_NOTFOUND);

        return false;
    }

    /**
     * Delete an item
     *
     * @param string $id
     * @return void
     **/
    public function delete($id)
    {
        if (isset($this->data[$id])) {
            unset ($this->data[$id]);
        }

        return true;
    }

    /**
     * Get the result code
     *
     * @return ResultCode
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    /**
     * Set the result code
     *
     * @param ResultCode $resultCode
     * @return Memcached
     */
    public function setResultCode($resultCode)
    {
        $this->resultCode = $resultCode;

        return $this;
    }
}
