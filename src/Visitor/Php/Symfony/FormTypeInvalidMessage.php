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
final class FormTypeInvalidMessage extends BasePHPVisitor implements NodeVisitor
{
    use FormTrait;

    public function enterNode(Node $node)
    {
        if (!$this->isFormType($node)) {
            return;
        }

        if (!$node instanceof Node\Expr\Array_) {
            return;
        }

        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            if ('invalid_message' !== $item->key->value) {
                continue;
            }

            if (!$item->value instanceof Node\Scalar\String_) {
                $this->addError($item, 'Form label is not a scalar string');

                continue;
            }

            $label = $item->value->value;
            if (empty($label)) {
                continue;
            }

            $this->addLocation($label, $node->getAttribute('startLine'), $node, ['domain' => 'validators']);
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
