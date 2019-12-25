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

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->sourceLocations);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->sourceLocations);
    }

    public function addLocation(SourceLocation $location): void
    {
        $this->sourceLocations[] = $location;
    }

    public function addError(Error $error): void
    {
        $this->errors[] = $error;
    }

    public function first(): ?SourceLocation
    {
        if (empty($this->sourceLocations)) {
            return null;
        }

        return reset($this->sourceLocations);
    }

    public function get(string $key): ?SourceLocation
    {
        if (!isset($this->sourceLocations[$key])) {
            return null;
        }

        return $this->sourceLocations[$key];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
