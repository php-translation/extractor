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
     */
    private $message;
    private $path;
    private $line;
    private $context;

    public function __construct(string $message, string $path, int $line, array $context = [])
    {
        $this->message = $message;
        $this->path = (string) $path;
        $this->line = $line;
        $this->context = $context;
    }

    /**
     * Create a source location from your current location.
     */
    public static function createHere(string $message, array $context = []): self
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2) as $trace) {
            // File is not set if we call from an anonymous context like an array_map function.
            if (isset($trace['file'])) {
                break;
            }
        }

        return new self($message, $trace['file'], $trace['line'], $context);
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

    public function getContext(): array
    {
        return $this->context;
    }
}
