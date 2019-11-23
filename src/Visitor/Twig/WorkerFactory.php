<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Twig;

use Twig\Environment;

/**
 * Create a Worker depending on what version of Twig is installed.
 *
 * @author Jeroen Spee
 */
final class WorkerFactory
{
    /**
     * @return Worker|LegacyWorker
     */
    public static function create()
    {
        if(-1 === version_compare(Environment::VERSION, '3.0')) {
            return new LegacyWorker();
        }

        return new Worker();
    }
}
