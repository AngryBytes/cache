<?php
/**
 * Cache.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * Cache
 *
 * Cache frontend, takes care of the forward facing interface for caching
 *
 * @category        AngryBytes
 * @package         Cache
 */
class Cache
{
    /**
     * Cache backend
     *
     * @var Adapter
     **/
    private $backend;

    /**
     * Default life time
     *
     * @var int
     **/
    private $defaultLifeTime = 3600;

    /**
     * Cache id prefix
     *
     * @var string
     **/
    private $idPrefix = '';

    /**
     * Constructor
     *
     * @return void
     **/
    public function __construct(Adapter $backend)
    {
        $this->setBackend($backend);
    }

    /**
     * Save something in the cache
     *
     * @param mixed  $data
     * @param string $id
     * @param int    $lifeTime
     * @access public
     * @return bool
     */
    public function save($data, $id, $lifeTime = -1)
    {
        return $this->getBackend()->save(
            $this->serialize($data),
            $this->getIdPrefixed($id),
            $this->getLifeTime($lifeTime)
        );
    }

    /**
     * Load something from the cache
     *
     * @param  string               $id
     * @return mixed|ResultNotFound
     **/
    public function load($id)
    {
        $value = $this->getBackend()->load(
            $this->getIdPrefixed($id)
        );

        if ($value instanceof ResultNotFound) {
            return $value;
        }

        return $this->unserialize($value);
    }

    /**
     * Remove something from the cache
     *
     * @param  string $id
     * @return bool
     **/
    public function delete($id)
    {
        return $this->getBackend()->delete(
            $this->getIdPrefixed($id)
        );
    }

    /**
     * Create a cache key based on a variable number of inputs
     *
     * This method accepts alpha numeric strings, but also serializable objects.
     * All of these will be concatenated into a single id string.
     *
     * @param mixed $keyPart1
     * @param mixed $keyPart2
     * ...
     * @param  mixed  $keyPartN
     * @return string
     **/
    public static function key()
    {
        // Func accepts variable number of arguments
        $params = func_get_args();

        // Sanity check
        if (count($params) === 0) {
            throw new InvalidArgumentException('key() expects parameters');
        }

        // Start the key
        $key = '';

        // Parts of the key that need serialisation
        $serializedParts = array();

        foreach ($params as $param) {
            if (ctype_alnum(str_replace(array('-', '_'), '', $param))) {
                // If alpha numeric (with dashes and underscores), add to key
                $key .= $param . '-';
            } else {
                // All other parts are serialized at the end
                $serializedParts[] = $param;
            }
        }

        // Add hash of serialized part to result
        if (count($serializedParts) > 0) {
            // Serialize and hash parts
            $serialized = substr(
                md5($this->serialize($serializedParts)),
                0, 5
            );

            // Return with serialized included
            return $key . $serialized;
        }

        // Or return the alphanumeric key
        return rtrim($key, '-');
    }

    /**
     * Get the default life time
     *
     * @return int
     */
    public function getDefaultLifeTime()
    {
        return $this->defaultLifeTime;
    }

    /**
     * Set the default life time
     *
     * @param  int   $defaultLifeTime
     * @return Cache
     */
    public function setDefaultLifeTime($defaultLifeTime)
    {
        $this->defaultLifeTime = $defaultLifeTime;

        return $this;
    }

    /**
     * Get the cache id prefix
     *
     * @return string
     */
    public function getIdPrefix()
    {
        return $this->idPrefix;
    }

    /**
     * Set the cache id prefix
     *
     * @param  string $prefix
     * @return Cache
     */
    public function setIdPrefix($prefix)
    {
        $this->idPrefix = $prefix;

        return $this;
    }

    /**
     * Add an id prefix
     *
     * @param  string $prefix
     * @return Cache
     **/
    public function addIdPrefix($prefix)
    {
        $this->idPrefix .= $prefix;

        return $this;
    }

    /**
     * Get the cache backend adapter
     *
     * @return Adapter
     */
    public function getBackend()
    {
        return $this->backend;
    }

    /**
     * Set the cache backend adapter
     *
     * @param  Adapter $backend
     * @return Cache
     */
    public function setBackend(Adapter $backend)
    {
        $this->backend = $backend;

        return $this;
    }

    /**
     * Get the full, prefixed id
     *
     * @see setIdPrefix()
     *
     * @param  string $id
     * @return string
     */
    private function getIdPrefixed($id)
    {
        if (strlen($this->getIdPrefix()) > 0) {
            return $this->getIdPrefix() . '-' . $id;
        }

        return $id;
    }

    /**
     * Get the life time to use
     *
     * Will return default life time if $lifeTime < 0
     *
     * @see setDefaultLifeTime()
     *
     * @param  int $lifeTime
     * @return int
     **/
    private function getLifeTime($lifeTime)
    {
        if ($lifeTime < 0) {
            return $this->getDefaultLifeTime();
        }

        return $lifeTime;
    }

    /**
     * Serialize some data into string form
     *
     * @param  mixed  $data
     * @return string
     **/
    private function serialize($data)
    {
        return serialize($data);
    }

    /**
     * Unserialize some data
     *
     * @param  string $data
     * @return mixed
     **/
    private function unserialize($data)
    {
        return unserialize($data);
    }
}
