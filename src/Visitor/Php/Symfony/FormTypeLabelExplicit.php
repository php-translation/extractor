<?php

namespace Translation\Extractor\Visitor\Php\Symfony;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

class FormTypeLabelExplicit extends BasePHPVisitor implements NodeVisitor
{
    public function enterNode(Node $node)
    {
        // only Traverse *Type
        if ($node instanceof Stmt\Class_) {
            if (substr($node->name, -4) !== 'Type') {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
        }

        // we could have chosen to traverse specifically the buildForm function or ->add()
        // we will probably miss some easy to catch instances when the actual array of options
        // is provided statically or through another function.
        // I don't see any disadvantages now to simply parsing arrays and JMSTranslationBundle has
        // been doing it like this for quite some time without major problems.
        if ($node instanceof Node\Expr\Array_) {
            foreach ($node->items as $item) {
                if (!$item->key instanceof Node\Scalar\String_) {
                    continue;
                }

                if ($item->key->value !== 'label') {
                    continue;
                }

                if (!$item->value instanceof Node\Scalar\String_) {
                    continue;
                }

                $label = $item->value->value;
                if (empty($label)) {
                    continue;
                }

                $sl = new SourceLocation($label, $this->getAbsoluteFilePath(), $node->getAttribute('startLine'));
                $this->collection->addLocation($sl);
            }
        }
    }

    public function leaveNode(Node $node) {}

    public function beforeTraverse(array $nodes) {}
    public function afterTraverse(array $nodes) {}
}
