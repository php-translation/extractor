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
    private $message;
    private $path;
    private $line;

    public function __construct(string $message, string $path, int $line)
    {
        $this->message = $message;
        $this->path = (string) $path;
        $this->line = $line;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getLine(): int
    {
        return $this->line;
    }
}
