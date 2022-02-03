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

final class FormTypeHelp extends AbstractFormType implements NodeVisitor
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

        $helpNode = null;
        $domain = null;
        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }
            if ('translation_domain' === $item->key->value) {
                // Try to find translation domain
                if ($item->value instanceof Node\Scalar\String_) {
                    $domain = $item->value->value;
                }
            } elseif ('help' === $item->key->value) {
                $helpNode = $item;
            }
        }

        if (null === $helpNode) {
            return null;
        }

        if ($helpNode->value instanceof Node\Scalar\String_) {
            $line = $helpNode->value->getAttribute('startLine');
            if (null !== $location = $this->getLocation($helpNode->value->value, $line, $helpNode, ['domain' => $domain])) {
                $this->lateCollect($location);
            }
        } else {
            $this->addError($helpNode, 'Form help is not a scalar string');
        }

        return null;
    }
}
