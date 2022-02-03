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
final class FormTypeEmptyValue extends BasePHPVisitor implements NodeVisitor
{
    use FormTrait;

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node): ?Node
    {
        if (!$this->isFormType($node)) {
            return null;
        }

        if (!$node instanceof Node\Expr\Array_) {
            return null;
        }

        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            if ('empty_value' !== $item->key->value) {
                continue;
            }

            if ($item->value instanceof Node\Scalar\String_) {
                $this->storeValue($node, $item);
            } elseif ($item->value instanceof Node\Expr\Array_) {
                foreach ($item->value->items as $arrayItem) {
                    $this->storeValue($node, $arrayItem);
                }
            }
        }

        return null;
    }

    private function storeValue(Node $node, $item): void
    {
        if (!$item->value instanceof Node\Scalar\String_) {
            $this->addError($item, 'Form label is not a scalar string');

            return;
        }

        $label = $item->value->value;
        if (empty($label)) {
            return;
        }

        $this->addLocation($label, $node->getAttribute('startLine'), $node);
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
    public function beforeTraverse(array $nodes): ?Node
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
