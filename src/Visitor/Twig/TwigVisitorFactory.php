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
 * Create a TwigVisitor depending on what version of Twig is installed.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TwigVisitorFactory
{
    /**
     * @return TwigVisitor
     */
    public static function create()
    {
        switch (-1) {
            case version_compare(Environment::VERSION, '2.0'):
                return new Twig1Visitor();
            case version_compare(Environment::VERSION, '3.0'):
                return new Twig2Visitor();
            default:
                return new Twig3Visitor();
        }
    }
}
