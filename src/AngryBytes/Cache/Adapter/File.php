<?php
/**
 * File.php
 *
 * @category        AngryBytes
 * @package         Cache
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Cache\Adapter;

use AngryBytes\Cache\Adapter;

use AngryBytes\Cache\ResultNotFound;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * File
 *
 * Cache backend adapter using the file system
 *
 * @category        AngryBytes
 * @package         Cache
 */
class File extends Adapter
{
    /**
     * Directory
     *
     * @var string
     **/
    private $directory;

    /**
     * Constructor
     *
     * @param string $directory
     * @return void
     **/
    public function __construct($directory)
    {
        $this->setDirectory($directory);
    }

    /**
     * Get the cache directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set the cache directory
     *
     * @param string $directory
     * @return File
     */
    public function setDirectory($directory)
    {
        // Sanity check
        if (!file_exists($directory)) {
            throw new InvalidArgumentException(
                '"' . $directory . '" does not exist'
            );
        }

        $this->directory = $directory;

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
        $bytes = file_put_contents(
            $this->getFileNameForId($id),
            $this->wrapLifeTime($data, $lifeTime)
        );

        return $bytes > 0;
    }

    /**
     * Load an item from the cache
     *
     * @param  string               $id
     * @return mixed|ResultNotFound
     **/
    public function load($id)
    {
        // If there's no matching file, remove
        if (!file_exists($this->getFileNameForId($id))) {
            return new ResultNotFound($id);
        }

        $wrappedData = file_get_contents($this->getFileNameForId($id));

        list($lifeTime, $data) = $this->unWrapLifeTime($wrappedData);

        // Invalid time
        if ($lifeTime < time()) {

            // Delete the item
            $this->delete($id);

            // Not found
            return new ResultNotFound($id);
        }

        return $data;
    }

    /**
     * Delete an item from the cache
     *
     * @param  string $id
     * @return bool
     **/
    public function delete($id)
    {
        if (file_exists($this->getFileNameForId($id))) {
            return unlink($this->getFileNameForId($id));
        }

        return true;
    }

    /**
     * Get the full file name for a cache id
     *
     * @param  string $id
     * @return string
     **/
    private function getFileNameForId($id)
    {
        return $this->getDirectory() . '/' . $id . '.cache';
    }

    /**
     * Wrap lifetime into data string
     *
     * @param string $data
     * @param int $lifeTime
     * @return string
     **/
    private function wrapLifeTime($data, $lifeTime)
    {
        return (time() + $lifeTime) . '|' . $data;
    }

    /**
     * Unwrap lifetime from data
     *
     * @param string $wrappedData
     * @return void
     **/
    private function unWrapLifeTime($wrappedData)
    {
        $time = substr(
            $wrappedData,
            0,
            strpos($wrappedData, '|')
        );

        $data = substr(
            $wrappedData,
            strpos($wrappedData, '|') + 1
        );

        return array($time, $data);
    }
}

