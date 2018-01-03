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
 * @author Rein Baarsma <rein@solidwebcode.com>
 */
final class FormTypeLabelExplicit extends BasePHPVisitor implements NodeVisitor
{
    public function enterNode(Node $node)
    {
        // only Traverse *Type
        if ($node instanceof Stmt\Class_) {
            if ('Type' !== substr($node->name, -4)) {
                return;
            }
        }

        // we could have chosen to traverse specifically the buildForm function or ->add()
        // we will probably miss some easy to catch instances when the actual array of options
        // is provided statically or through another function.
        // I don't see any disadvantages now to simply parsing arrays and JMSTranslationBundle has
        // been doing it like this for quite some time without major problems.
        if (!$node instanceof Node\Expr\Array_) {
            return;
        }

        $labelNode = null;
        $domain = null;
        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            if ('translation_domain' === $item->key->value) {
                if ($item->value instanceof Node\Scalar\String_) {
                    $domain = $item->value->value;
                } elseif ($item->value instanceof Node\Expr\ConstFetch && 'false' === $item->value->name->toString()) {
                    $domain = false;
                }
            }

            if ('label' !== $item->key->value) {
                continue;
            }

            if ($item->value instanceof Node\Expr\ConstFetch) {
                // This might be boolean "false"
                if ('false' === $item->value->name->toString()) {
                    continue;
                }
            }

            if (!$item->value instanceof Node\Scalar\String_) {
                $this->addError($item, 'Form label is not a scalar string');

                continue;
            }

            $label = $item->value->value;
            if (empty($label)) {
                continue;
            }

            $labelNode = $item;
        }

        if ($labelNode && false !== $domain) {
            $this->addLocation($label, $node->getAttribute('startLine'), $item, ['domain' => $domain]);
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
