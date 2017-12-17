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
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitor;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class FormTypePlaceholder extends BasePHPVisitor implements NodeVisitor
{
    public function enterNode(Node $node)
    {
        // only Traverse *Type
        if ($node instanceof Stmt\Class_) {
            if ('Type' !== substr($node->name, -4)) {
                return;
            }
        }

        if (!$node instanceof Node\Expr\Array_) {
            return;
        }

        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            if ('placeholder' === $item->key->value) {
                if ($item->value instanceof Node\Scalar\String_) {
                    $line = $item->value->getAttribute('startLine');
                    $this->addLocation($item->value->value, $line, $item);
                } else {
                    $this->addError($item, 'Form placeholder is not a scalar string');
                }
            }
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function beforeTraverse(array $nodes)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
