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
 *
 * @deprecated Use Worker
 */
final class WorkerTranslationBlock
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
        if ($node instanceof TransNode) {
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

        return $node;
    }
}
