<?php
/**
 * ResultNotFound.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         Cache
 * @subpackage      Result
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Abc\Cache;

/**
 * ResultNotFound
 *
 * Result not found identifier
 *
 * @category        Abc
 * @package         Cache
 * @subpackage      Result
 */
class ResultNotFound
{
    /**
     * Id that was not found
     *
     * @var string
     **/
    private $id;

    /**
     * Constructor
     *
     * @param string $id
     * @return void
     **/
    public function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * Get the id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id
     *
     * @param string $id
     * @return ResultNotFound
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * String overload
     *
     * @return void
     **/
    public function __toString()
    {
        return 'Cache result "' . $this->id . '" was not found';
    }
}
