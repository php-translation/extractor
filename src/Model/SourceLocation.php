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
final class SourceLocation
{
    /**
     * Translation key.
     *
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
     * @var array
     */
    private $context;

    /**
     * @param string $message
     * @param string $path
     * @param int    $line
     * @param array  $context
     */
    public function __construct($message, $path, $line, array $context = [])
    {
        $this->message = $message;
        $this->path = (string) $path;
        $this->line = $line;
        $this->context = $context;
    }

    /**
     * Create a source location from your current location.
     *
     * @param string $message
     * @param array  $context
     *
     * @return SourceLocation
     */
    public static function createHere($message, array $context = [])
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        return new self($message, $trace[0]['file'], $trace[0]['line'], $context);
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

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
