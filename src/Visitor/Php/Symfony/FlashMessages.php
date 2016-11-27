<?php

namespace Translation\Extractor\Visitor\Php\Symfony;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\BaseVisitor;

class FlashMessages extends BaseVisitor implements NodeVisitor
{
    public function beforeTraverse(array $nodes)
    {
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\MethodCall) {
            if (!is_string($node->name)) {
                return;
            }

            $name = $node->name;
            $caller = $node->var;
            $callerName = $caller->name;

            /*
             * Make sure the caller is from a variable named "this" or a function called "getFlashbag"
             */
            //If $this->addFlash()  or xxx->getFlashbag()->add()
            if (('addFlash' === $name && $callerName === 'this' && $caller instanceof Node\Expr\Variable) ||
                ('add' === $name && $callerName === 'getFlashBag' && $caller instanceof Node\Expr\MethodCall)
            ) {
                /*
                 * Start reading the argument
                 */

                // second argument must be a string
                if (!$node->args[1]->value instanceof Node\Scalar\String_) {
                    return;
                }

                $label = $node->args[1]->value->value;
                if (empty($label)) {
                    return;
                }

                $this->collection->addLocation(new SourceLocation($label, $this->getAbsoluteFilePath(), $node->getAttribute('startLine')));

                return;
            }
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
