<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Php;

use PhpParser\Node;
use Translation\Extractor\Visitor\BaseVisitor;

/**
 * Base class for PHP visitors.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class BasePHPVisitor extends BaseVisitor
{
    protected function getStringArgument(Node\Expr\MethodCall $node, int $index): ?string
    {
        if (!isset($node->args[$index])) {
            return null;
        }

        $label = $this->getStringValue($node->args[$index]->value);
        if (empty($label)) {
            return null;
        }

        return $label;
    }

    /**
     * @param Node $node
     *
     * @return string|null
     */
    private function getStringValue(Node $node)
    {
        if ($node instanceof Node\Scalar\String_) {
            return $node->value;
        }

        if ($node instanceof Node\Expr\BinaryOp\Concat) {
            $left = $this->getStringValue($node->left);
            if (null === $left) {
                return;
            }

            $right = $this->getStringValue($node->right);
            if (null === $right) {
                return;
            }

            return $left.$right;
        }
    }
}
