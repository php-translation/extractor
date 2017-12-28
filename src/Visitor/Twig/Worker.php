<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Twig;

use Symfony\Bridge\Twig\Node\TransNode;
use Translation\Extractor\Model\Error;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Model\SourceLocation;

/**
 * The Worker tha actually extract the translations.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class Worker
{
    const UNDEFINED_DOMAIN = 'messages';

    private $stack = [];

    /**
     * @param \Twig_Node|\Twig_NodeInterface $node
     * @param SourceCollection               $collection
     * @param callable                       $getAbsoluteFilePath
     *
     * @return \Twig_Node|\Twig_NodeInterface
     */
    public function work($node, SourceCollection $collection, callable $getAbsoluteFilePath)
    {
        $this->stack[] = $node;
        if ($node instanceof \Twig_Node_Expression_Filter && $node->getNode('node') instanceof \Twig_Node_Expression_Constant) {
            $domain = null;
            if ('trans' === $node->getNode('filter')->getAttribute('value')) {
                $domain = $this->getReadDomainFromArguments($node->getNode('arguments'), 1);
            } elseif ('transchoice' === $node->getNode('filter')->getAttribute('value')) {
                $domain = $this->getReadDomainFromArguments($node->getNode('arguments'), 2);
            }

            if ($domain) {
                try {
                    $context = $this->extractContextFromJoinedFilters();
                } catch (\LogicException $e) {
                    $collection->addError(new Error($e->getMessage(), $getAbsoluteFilePath(), $node->getTemplateLine()));
                }
                $context['domain'] = $domain;
                $collection->addLocation(
                    new SourceLocation(
                        $node->getNode('node')->getAttribute('value'),
                        $getAbsoluteFilePath(),
                        $node->getTemplateLine(),
                        $context
                    )
                );
            }
        } elseif ($node instanceof TransNode) {
            // extract trans nodes
            $domain = self::UNDEFINED_DOMAIN;
            if ($node->hasNode('domain') && null !== $node->getNode('domain')) {
                $domain = $this->getReadDomainFromNode($node->getNode('domain'));
            }

            $collection->addLocation(new SourceLocation(
                $node->getNode('body')->getAttribute('data'),
                $getAbsoluteFilePath(),
                $node->getTemplateLine(),
                ['domain' => $domain]
            ));
        }

        return $node;
    }

    /**
     * @return array
     */
    private function extractContextFromJoinedFilters()
    {
        $context = [];
        for ($i = count($this->stack) - 2; $i >= 0; $i -= 1) {
            if (!$this->stack[$i] instanceof \Twig_Node_Expression_Filter) {
                break;
            }
            $name = $this->stack[$i]->getNode('filter')->getAttribute('value');
            if ('trans' === $name) {
                break;
            } elseif ('desc' === $name) {
                $arguments = $this->stack[$i]->getNode('arguments');
                if (!$arguments->hasNode(0)) {
                    throw new \LogicException(sprintf('The "%s" filter requires exactly one argument, the description text.', $name));
                }
                $text = $arguments->getNode(0);
                if (!$text instanceof \Twig_Node_Expression_Constant) {
                    throw new \LogicException(sprintf('The first argument of the "%s" filter must be a constant expression, such as a string.', $name));
                }
                $context['desc'] = $text->getAttribute('value');
            }
        }

        return $context;
    }

    /**
     * @param \Twig_Node $arguments
     * @param int        $index
     *
     * @return string|null
     */
    private function getReadDomainFromArguments(\Twig_Node $arguments, $index)
    {
        if ($arguments->hasNode('domain')) {
            $argument = $arguments->getNode('domain');
        } elseif ($arguments->hasNode($index)) {
            $argument = $arguments->getNode($index);
        } else {
            return self::UNDEFINED_DOMAIN;
        }

        return $this->getReadDomainFromNode($argument);
    }

    /**
     * @param \Twig_Node $node
     *
     * @return string|null
     */
    private function getReadDomainFromNode(\Twig_Node $node)
    {
        if ($node instanceof \Twig_Node_Expression_Constant) {
            return $node->getAttribute('value');
        }

        return self::UNDEFINED_DOMAIN;
    }
}
