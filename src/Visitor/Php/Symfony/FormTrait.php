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
use PhpParser\Node\Stmt;

trait FormTrait
{
    private $isFormType = false;

    private $arrayNodeVisited = [];

    /**
     * Check if this node is a form type.
     *
     * @param Node $node
     *
     * @return bool
     */
    private function isFormType(Node $node)
    {
        // only Traverse *Type
        if ($node instanceof Stmt\Class_) {
            $this->isFormType = 'Type' === substr($node->name, -4);
        }

        return $this->isFormType;
    }

    /**
     * Check if a node have been visited.
     *
     * @param Node $node
     *
     * @return bool
     */
    private function isKnownNode(Node $node)
    {
        $hash = spl_object_hash($node);
        if (isset($this->arrayNodeVisited[$hash])) {
            return true;
        }
        $this->arrayNodeVisited[$hash] = true;

        return false;
    }
}
