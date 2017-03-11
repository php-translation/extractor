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
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\BaseVisitor;
use Twig_Environment;
use Twig_NodeInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @deprecated Use Twig1Visitor. Will be removed in 2.0
 */
final class TranslationBlock extends BaseVisitor implements \Twig_NodeVisitorInterface
{
    public function enterNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        if ($node instanceof TransNode) {
            $id = $node->getNode('body')->getAttribute('data');
            $domain = 'messages';
            if ($node->hasNode('domain')) {
                $domain = $node->getNode('domain')->getAttribute('value');
            }

            $source = new SourceLocation($id, $this->getAbsoluteFilePath(), $node->getLine(), ['domain' => $domain]);
            $this->collection->addLocation($source);
        }

        return $node;
    }

    public function leaveNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
