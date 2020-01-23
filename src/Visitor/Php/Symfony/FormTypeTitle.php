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
final class FormTypeTitle extends AbstractFormType implements NodeVisitor
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

        parent::enterNode($node);

        if (!$node instanceof Node\Expr\Array_) {
            return null;
        }

        $titleNode = null;
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
            } elseif ('attr' === $item->key->value && $item->value instanceof Node\Expr\Array_) {
                foreach ($item->value->items as $attrValue) {
                    if (!$attrValue->key instanceof Node\Scalar\String_) {
                        continue;
                    }
                    if ('title' === $attrValue->key->value) {
                        $titleNode = $attrValue;

                        break;
                    }
                }
            }
        }

        if (null === $titleNode) {
            return null;
        }

        if ($titleNode->value instanceof Node\Scalar\String_) {
            $line = $titleNode->value->getAttribute('startLine');
            if (null !== $location = $this->getLocation($titleNode->value->value, $line, $titleNode, ['domain' => $domain])) {
                $this->lateCollect($location);
            }
        } else {
            $this->addError($titleNode, 'Form field title is not a scalar string');
        }

        return null;
    }
}
