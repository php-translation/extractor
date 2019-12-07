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

    /**
     * Check if this node is a form type.
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
