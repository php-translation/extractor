<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Php\Symfony;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ContainerAwareTrans extends BasePHPVisitor implements NodeVisitor
{
    public function beforeTraverse(array $nodes)
    {
    }

    public function enterNode(Node $node)
    {
        if (!$node instanceof Node\Expr\MethodCall) {
            return;
        }

        if (!is_string($node->name) && !$node->name instanceof Node\Identifier) {
            return;
        }
        $name = (string) $node->name;

        //If $this->get('translator')->trans('foobar')
        if ('trans' === $name) {
            $label = $this->getStringArgument($node, 0);
            $domain = $this->getStringArgument($node, 2);

            $this->addLocation($label, $node->getAttribute('startLine'), $node, ['domain' => $domain]);
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
