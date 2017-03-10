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
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Model\SourceLocation;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class WorkerTranslationBlock
{
    const UNDEFINED_DOMAIN = 'messages';

    /**
     * @param \Twig_Node|\Twig_NodeInterface $node
     * @param SourceCollection               $collection
     * @param callable                       $getAbsoluteFilePath
     *
     * @return \Twig_Node|\Twig_NodeInterface
     */
    public function work($node, SourceCollection $collection, callable $getAbsoluteFilePath)
    {
        if (false && $node instanceof TransNode) {
            $id = $node->getNode('body')->getAttribute('data');
            $domain = 'messages';
            if ($node->hasNode('domain')) {
                if (null !== $domainNode = $node->getNode('domain')) {
                    $domain = $domainNode->getAttribute('value');
                }
            }

            $source = new SourceLocation($id, $getAbsoluteFilePath(), $node->getTemplateLine(), ['domain' => $domain]);
            $collection->addLocation($source);
        }

        if (
            $node instanceof \Twig_Node_Expression_Filter &&
            'trans' === $node->getNode('filter')->getAttribute('value') &&
            $node->getNode('node') instanceof \Twig_Node_Expression_Constant
        ) {
            // extract constant nodes with a trans filter
            $collection->addLocation(new SourceLocation(
                $node->getNode('node')->getAttribute('value'),
                $getAbsoluteFilePath(),
                $node->getTemplateLine(),
                ['domain' => $this->getReadDomainFromArguments($node->getNode('arguments'), 1)]
            ));
        } elseif (
            $node instanceof \Twig_Node_Expression_Filter &&
            'transchoice' === $node->getNode('filter')->getAttribute('value') &&
            $node->getNode('node') instanceof \Twig_Node_Expression_Constant
        ) {
            // extract constant nodes with a trans filter
            $collection->addLocation(new SourceLocation(
                $node->getNode('node')->getAttribute('value'),
                $getAbsoluteFilePath(),
                $node->getTemplateLine(),
                ['domain' => $this->getReadDomainFromArguments($node->getNode('arguments'), 2),]
            ));
        } elseif ($node instanceof TransNode) {
            // extract trans nodes
            $collection->addLocation(new SourceLocation(
                $node->getNode('body')->getAttribute('data'),
                $getAbsoluteFilePath(),
                $node->getTemplateLine(),
                ['domain' => $node->hasNode('domain') ? $this->getReadDomainFromNode($node->getNode('domain')) : self::UNDEFINED_DOMAIN]
            ));
        }

        return $node;
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
