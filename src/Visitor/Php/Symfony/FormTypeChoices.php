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
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

/**
 * @author Rein Baarsma <rein@solidwebcode.com>
 */
final class FormTypeChoices extends BasePHPVisitor implements NodeVisitor
{
    /**
     * @var int defaults to major version 3
     */
    protected $symfonyMajorVersion = 3;

    private $variables = [];

    private $state;

    /**
     * @param int $sfMajorVersion
     */
    public function setSymfonyMajorVersion($sfMajorVersion)
    {
        $this->symfonyMajorVersion = $sfMajorVersion;
    }

    public function enterNode(Node $node)
    {
        // only Traverse *Type
        if ($node instanceof Stmt\Class_) {
            if ('Type' !== substr($node->name, -4)) {
                return;
            }
        }

        if (null === $this->state && $node instanceof Node\Expr\Assign) {
            $this->state = 'variable';
        } elseif ('variable' === $this->state && $node instanceof Node\Expr\Variable) {
            $this->variables['__variable-name'] = $node->name;
            $this->state = 'value';
        } elseif ('value' === $this->state && $node instanceof Node\Expr\Array_) {
            $this->variables[$this->variables['__variable-name']] = $node;
            $this->state = null;
        } else {
            $this->state = null;
        }

        // symfony 3 displays key by default, where symfony 2 displays value
        $useKey = 3 === $this->symfonyMajorVersion;

        // remember choices in this node
        $choicesNodes = [];

        // loop through array
        if (!$node instanceof Node\Expr\Array_) {
            return;
        }

        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            if ('choices_as_values' === $item->key->value) {
                $useKey = true;

                continue;
            }

            if ('choices' !== $item->key->value) {
                continue;
            }

            $choicesNodes[] = $item->value;
        }

        if (0 === count($choicesNodes)) {
            return;
        }

        // probably will be only 1, but who knows
        foreach ($choicesNodes as $choices) {
            if ($choices instanceof Node\Expr\Variable && isset($this->variables[$choices->name])) {
                $choices = $this->variables[$choices->name];
            } elseif (!$choices instanceof Node\Expr\Array_) {
                $this->addError($choices, 'Form choice is not an array');

                continue;
            }

            foreach ($choices->items as $citem) {
                $labelNode = $useKey ? $citem->key : $citem->value;
                if (!$labelNode instanceof Node\Scalar\String_) {
                    $this->addError($citem, 'Choice label is not a scalar string');

                    continue;
                }

                $sl = new SourceLocation($labelNode->value, $this->getAbsoluteFilePath(), $choices->getAttribute('startLine'));
                $this->collection->addLocation($sl);
            }
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function beforeTraverse(array $nodes)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
