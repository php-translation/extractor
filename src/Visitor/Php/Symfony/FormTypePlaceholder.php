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

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class FormTypePlaceholder extends AbstractFormType implements NodeVisitor
{
    use FormTrait;

    private $arrayNodeVisited = [];

    public function enterNode(Node $node)
    {
        if (!$this->isFormType($node)) {
            return;
        }
        parent::enterNode($node);

        if (!$node instanceof Node\Expr\Array_) {
            return;
        }

        $placeholderNode = null;
        $domain = null;
        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }
            if ('translation_domain' === $item->key->value) {
                // Try to find translation domain
                if ($item->value instanceof Node\Scalar\String_) {
                    $domain = $item->value->value;
                } elseif ($item->value instanceof Node\Expr\ConstFetch && 'false' === $item->value->name->toString()) {
                    $domain = false;
                }
            } elseif ('placeholder' === $item->key->value) {
                $placeholderNode = $item;
            } elseif ('attr' === $item->key->value && $item->value instanceof Node\Expr\Array_) {
                foreach ($item->value->items as $attrValue) {
                    if ('placeholder' === $attrValue->key->value) {
                        $placeholderNode = $attrValue;

                        break;
                    }
                }
            }
        }

        if (null !== $placeholderNode) {
            /**
             * Make sure we do not visit the same placeholder node twice.
             */
            $hash = spl_object_hash($placeholderNode);
            if (isset($this->arrayNodeVisited[$hash])) {
                return;
            }
            $this->arrayNodeVisited[$hash] = true;

            if ($placeholderNode->value instanceof Node\Scalar\String_) {
                $line = $placeholderNode->value->getAttribute('startLine');
                if (null !== $location = $this->getLocation($placeholderNode->value->value, $line, $placeholderNode, ['domain' => $domain])) {
                    $this->lateCollect($location);
                }
            } elseif ($placeholderNode->value instanceof Node\Expr\ConstFetch && 'false' === $placeholderNode->value->name->toString()) {
                // 'placeholder' => false,
                // Do noting
            } else {
                $this->addError($placeholderNode, 'Form placeholder is not a scalar string');
            }
        }
    }
}
