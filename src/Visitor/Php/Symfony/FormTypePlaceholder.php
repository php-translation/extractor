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
                    if (!$attrValue->key instanceof Node\Scalar\String_) {
                        continue;
                    }
                    if ('placeholder' === $attrValue->key->value) {
                        $placeholderNode = $attrValue;

                        break;
                    }
                }
            }
        }

        if (null === $placeholderNode) {
            return null;
        }

        /**
         * Make sure we do not visit the same placeholder node twice.
         *
         * The placeholder information is not always in the same place:
         * * it can be in Type options (for example when using `ChoiceType`)
         * * it can be in `attr` (for example when using `TextType`)
         *
         * @see https://github.com/php-translation/extractor/pull/114#issuecomment-400329507
         */
        $hash = spl_object_hash($placeholderNode);
        if (isset($this->arrayNodeVisited[$hash])) {
            return null;
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

        return null;
    }
}
