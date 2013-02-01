<?php
/**
 * AdapterInterface.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Adapter
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache\Adapter\Memcached;

/**
 * AdapterInterface
 *
 * Used to facilitate adapter/testing switching
 *
 * @category        AngryBytes
 * @package         Cache
 * @subpackage      Adapter
 */
interface AdapterInterface
{
    public function addServer($host, $port, $weight = 0);

    public function set($id, $data, $lifeTime = 0);

    public function get($id);

    public function delete($id);
}
