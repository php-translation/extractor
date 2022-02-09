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
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;

/**
 * Extract translations from classes implementing
 * Translation\Extractor\Model\SourceLocation\TranslationSourceLocationContainer.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class SourceLocationContainerVisitor extends BasePHPVisitor implements NodeVisitor
{
    /**
     * @var string
     */
    private $namespace = '';

    /**
     * @var array
     */
    private $useStatements = [];

    /**
     * {@inheritdoc}
     */
    public function beforeTraverse(array $nodes): ?Node
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            if (isset($node->name)) {
                // Save namespace of this class for later.
                $this->namespace = implode('\\', $node->name->parts);
            }
            $this->useStatements = [];

            return null;
        }

        if ($node instanceof Node\Stmt\UseUse) {
            $key = isset($node->alias) ? $node->alias : $node->name->parts[\count($node->name->parts) - 1];
            $this->useStatements[(string) $key] = implode('\\', $node->name->parts);

            return null;
        }

        if (!$node instanceof Node\Stmt\Class_) {
            return null;
        }

        $isContainer = false;
        foreach ($node->implements as $interface) {
            $name = implode('\\', $interface->parts);
            if (isset($this->useStatements[$name])) {
                $name = $this->useStatements[$name];
            }

            if ('Translation\Extractor\TranslationSourceLocationContainer' === $name) {
                $isContainer = true;

                break;
            }
        }

        if (!$isContainer) {
            return null;
        }

        $sourceLocations = \call_user_func([$this->namespace.'\\'.$node->name, 'getTranslationSourceLocations']);

        foreach ($sourceLocations as $sourceLocation) {
            if (!$sourceLocation instanceof SourceLocation) {
                throw new \RuntimeException(sprintf('%s::getTranslationSourceLocations() was expected to return an array of SourceLocations, but got an array which contains an item of type %s.', $this->namespace.'\\'.$node->name, \gettype($sourceLocation)));
            }

            $this->collection->addLocation($sourceLocation);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node): ?Node
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(array $nodes): ?Node
    {
        return null;
    }
}
