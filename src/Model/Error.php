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
 * An error with the source code that occurred when extracting.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Error
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $line;

    /**
     * @param string $message
     * @param string $path
     * @param int    $line
     */
    public function __construct($message, $path, $line)
    {
        $this->message = $message;
        $this->path = (string) $path;
        $this->line = $line;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }
}
