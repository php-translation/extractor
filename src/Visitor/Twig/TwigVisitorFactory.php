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
    public static function create(): Twig2Visitor
    {
        return new Twig2Visitor();
    }
}
