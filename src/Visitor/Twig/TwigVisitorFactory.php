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

/**
 * Create a TwigVisitor depending on what version of Twig is installed.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TwigVisitorFactory
{
    /**
     * @return Twig1Visitor|Twig2Visitor
     */
    public static function create()
    {
        if (-1 === version_compare(\Twig_Environment::VERSION, '2.0')) {
            return new Twig1Visitor();
        }

        return new Twig2Visitor();
    }
}
