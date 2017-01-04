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
    /**
     * @param Node\Expr\MethodCall $node
     * @param int                  $index
     *
     * @return string|null
     */
    protected function getStringArgument(Node\Expr\MethodCall $node, $index)
    {
        if (!isset($node->args[$index])) {
            return;
        }

        if (!$node->args[$index]->value instanceof Node\Scalar\String_) {
            return;
        }

        $label = $node->args[$index]->value->value;
        if (empty($label)) {
            return;
        }

        return $label;
    }
}
