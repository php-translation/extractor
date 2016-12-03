<?php

namespace Translation\Extractor\Visitor\Php\Symfony;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

class FormTypeLabelImplicit extends BasePHPVisitor implements NodeVisitor
{
    public function enterNode(Node $node)
    {
        // only Traverse *Type
        if ($node instanceof Stmt\Class_) {
            if (substr($node->name, -4) !== 'Type') {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
        }

        // use add() function and look at first argument and if that's a string
        if ($node instanceof Node\Expr\MethodCall
            && ($node->name === "add" || $node->name === "create")
            && $node->args[0]->value instanceof Node\Scalar\String_) {

            // now make sure we don't have 'label' in the array of options
            $custom_label = false;
            if (count($node->args) >= 3) {
                if ($node->args[2]->value instanceof Node\Expr\Array_) {
                    foreach ($node->args[2]->value->items as $item) {
                        if ($item->key->value === 'label') {
                            $custom_label = true;
                        }
                    }
                }
                // actually there's another case here.. if the 3rd argument is anything else, it could well be
                // that label is set through a static array. This will not be a common use-case so yeah in this case
                // it may be the translation is double.
            }

            // only if no custom label was found, proceed
            if ($custom_label === false) {
                $label = $node->args[0]->value->value;
                if (!empty($label)) {
                    $sl = new SourceLocation($label, $this->getAbsoluteFilePath(), $node->getAttribute('startLine'));
                    $this->collection->addLocation($sl);
                }
            }
        }
    }

    public function leaveNode(Node $node) {}

    public function beforeTraverse(array $nodes) {}
    public function afterTraverse(array $nodes) {}
}
