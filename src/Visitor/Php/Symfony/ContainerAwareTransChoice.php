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
final class ContainerAwareTransChoice extends BasePHPVisitor implements NodeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function beforeTraverse(array $nodes): ?Node
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node): ?Node
    {
        if (!$node instanceof Node\Expr\MethodCall) {
            return null;
        }

        if (!\is_string($node->name) && !$node->name instanceof Node\Identifier) {
            return null;
        }
        $name = (string) $node->name;

        //If $this->get('translator')->transChoice('foobar')
        if ('transChoice' === $name) {
            $label = $this->getStringArgument($node, 0);
            if (null === $label) {
                return null;
            }
            $domain = $this->getStringArgument($node, 3);

            $this->addLocation($label, $node->getAttribute('startLine'), $node, ['domain' => $domain]);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node): ?Node
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(array $nodes): ?Node
    {
        return null;
    }
}
