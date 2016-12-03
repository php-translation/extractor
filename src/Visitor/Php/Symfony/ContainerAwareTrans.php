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
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

class ContainerAwareTrans extends BasePHPVisitor implements NodeVisitor
{
    public function beforeTraverse(array $nodes)
    {
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\MethodCall) {
            if (!is_string($node->name)) {
                return;
            }
            $name = $node->name;

            //If $this->get('translator')->trans('foobar')
            if ('trans' === $name) {
                $label = $this->getStringArgument($node, 0);
                $domain = $this->getStringArgument($node, 2);

                $source = new SourceLocation($label, $this->getAbsoluteFilePath(), $node->getAttribute('startLine'), ['domain' => $domain]);
                $this->collection->addLocation($source);
            }
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
