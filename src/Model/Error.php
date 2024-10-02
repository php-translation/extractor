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
    public function __construct(
        private readonly string $message,
        private readonly string $path,
        private readonly int $line,
    ) {
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
