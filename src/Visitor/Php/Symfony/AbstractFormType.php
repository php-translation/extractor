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
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class AbstractFormType extends BasePHPVisitor implements NodeVisitor
{
    /**
     * @var SourceLocation[]
     */
    private $sourceLocations = [];

    /**
     * @var null|string
     */
    private $defaultDomain;

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\MethodCall) {
            if (!is_string($node->name)) {
                return;
            }

            $name = strtolower($node->name);
            if ('setdefaults' === $name || 'replacedefaults' === $name || 'setdefault' === $name) {
                $this->parseDefaultsCall($node);
                return;
            }
        }
    }

    protected function lateCollect(SourceLocation $location)
    {
        $this->sourceLocations[] = $location;
    }

    public function leaveNode(Node $node)
    {
    }

    public function beforeTraverse(array $nodes)
    {
        $this->defaultDomain = null;
        $this->sourceLocations = [];
    }

    /**
     * From JMS Translation bundle
     * @param Node $node
     */
    private function parseDefaultsCall(Node $node)
    {
        static $returningMethods = array(
            'setdefaults' => true, 'replacedefaults' => true, 'setoptional' => true, 'setrequired' => true,
            'setallowedvalues' => true, 'addallowedvalues' => true, 'setallowedtypes' => true,
            'addallowedtypes' => true, 'setfilters' => true
        );

        $var = $node->var;
        while ($var instanceof Node\Expr\MethodCall) {
            if (!isset($returningMethods[strtolower($var->name)])) {
                return;
            }

            $var = $var->var;
        }

        if (!$var instanceof Node\Expr\Variable) {
            return;
        }

        // check if options were passed
        if (!isset($node->args[0])) {
            return;
        }

        if (isset($node->args[1])
            && $node->args[0]->value instanceof Node\Scalar\String_
            && $node->args[1]->value instanceof Node\Scalar\String_
            && 'translation_domain' === $node->args[0]->value->value
        ) {
            $this->defaultDomain =  $node->args[1]->value->value;
            return;
        }

        // ignore everything except an array
        if (!$node->args[0]->value instanceof Node\Expr\Array_) {
            return;
        }

        // check if a translation_domain is set as a default option
        $domain = null;
        foreach ($node->args[0]->value->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            if ('translation_domain' === $item->key->value) {
                if (!$item->value instanceof Node\Scalar\String_) {
                    continue;
                }

                $this->defaultDomain = $item->value->value;
            }
        }
    }

    public function afterTraverse(array $nodes)
    {
        /** @var SourceLocation $location */
        foreach ($this->sourceLocations as $location) {
            if (null !== $this->defaultDomain) {
                $context = $location->getContext();
                if (null === $context['domain']) {
                    $context['domain'] = $this->defaultDomain;
                    $location = new SourceLocation($location->getMessage(), $location->getPath(), $location->getLine(), $context);
                }
            }
            $this->collection->addLocation($location);
        }
        $this->sourceLocations = [];
    }
}
