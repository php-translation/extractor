<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Model;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class SourceCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var SourceLocation[]
     */
    private $sourceLocations = [];

    /**
     * @var Error[]
     */
    private $errors = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->sourceLocations);
    }

    public function count()
    {
        return count($this->sourceLocations);
    }

    /**
     * @param SourceLocation $location
     */
    public function addLocation(SourceLocation $location)
    {
        $this->sourceLocations[] = $location;
    }

    /**
     * @param Error $error
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return SourceLocation|null
     */
    public function first()
    {
        if (empty($this->sourceLocations)) {
            return;
        }

        return reset($this->sourceLocations);
    }

    /**
     * @param $key
     *
     * @return null|SourceLocation
     */
    public function get($key)
    {
        if (!isset($this->sourceLocations[$key])) {
            return;
        }

        return $this->sourceLocations[$key];
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
