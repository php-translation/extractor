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

use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Model\SourceLocation;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @deprecated Use Worker. Will be removed in 2.0.
 */
final class WorkerTranslationFilter
{
    /**
     * @param \Twig_Node|\Twig_NodeInterface $node
     * @param SourceCollection               $collection
     * @param callable                       $getAbsoluteFilePath
     *
     * @return \Twig_Node|\Twig_NodeInterface
     */
    public function work($node, SourceCollection $collection, callable $getAbsoluteFilePath)
    {
        if (!$node instanceof \Twig_Node_Expression_Filter) {
            return $node;
        }

        $name = $node->getNode('filter')->getAttribute('value');
        if ('trans' !== $name && 'transchoice' !== $name) {
            return $node;
        }

        $idNode = $node->getNode('node');
        if (!$idNode instanceof \Twig_Node_Expression_Constant) {
            // We can only extract constants
            return $node;
        }

        $id = $idNode->getAttribute('value');
        $index = 'trans' === $name ? 1 : 2;
        $domain = 'messages';
        $arguments = $node->getNode('arguments');
        if ($arguments->hasNode($index)) {
            $argument = $arguments->getNode($index);
            if (!$argument instanceof \Twig_Node_Expression_Constant) {
                return $node;
            }

            $domain = $argument->getAttribute('value');
        }

        $source = new SourceLocation($id, $getAbsoluteFilePath(), $node->getTemplateLine(), ['domain' => $domain]);
        $collection->addLocation($source);

        return $node;
    }
}
