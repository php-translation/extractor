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

use Twig_Environment;
use Twig_Node;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Twig2Visitor extends TwigVisitor implements \Twig_NodeVisitorInterface
{
    public function enterNode(Twig_Node $node, Twig_Environment $env)
    {
        return $this->doEnterNode($node);
    }

    public function leaveNode(Twig_Node $node, Twig_Environment $env)
    {
        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
