<?php

declare(strict_types=1);

namespace Translation\Extractor\Visitor\Php\Symfony;

use PhpParser\Node;
use PhpParser\Node\Stmt;

trait FormTrait
{
    private $isFormType = false;

    /**
     * Check if this node is a form type
     * @param Node $node
     */
    private function isFormType(Node $node): bool
    {
        // only Traverse *Type
        if ($node instanceof Stmt\Class_) {
            $this->isFormType = 'Type' === substr($node->name, -4);
        }

        return $this->isFormType;
    }
}
