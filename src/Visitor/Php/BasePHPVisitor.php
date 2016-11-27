<?php

namespace Translation\Extractor\Visitor\Php;

use PhpParser\Node;
use Translation\Extractor\Visitor\BaseVisitor;

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
