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
final class FlashMessage extends BasePHPVisitor implements NodeVisitor
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

        // This prevents dealing with some fatal edge cases when getting the callerName
        if (!\in_array($name, ['addFlash', 'add'])) {
            return null;
        }

        $caller = $node->var;
        // $caller might be "Node\Expr\New_"
        $callerName = isset($caller->name) ? (string) $caller->name : '';

        /*
         * Make sure the caller is from a variable named "this" or a function called "getFlashbag"
         */
        //If $this->addFlash()  or xxx->getFlashbag()->add()
        if (('addFlash' === $name && 'this' === $callerName && $caller instanceof Node\Expr\Variable) ||
            ('add' === $name && 'getFlashBag' === $callerName && $caller instanceof Node\Expr\MethodCall)
        ) {
            if (null !== $label = $this->getStringArgument($node, 1)) {
                $this->addLocation($label, $node->getAttribute('startLine'), $node);
            }
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
