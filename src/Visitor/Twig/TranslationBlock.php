<?php

namespace Translation\Extractor\Visitor\Twig;

use Symfony\Bridge\Twig\Node\TransNode;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\BaseVisitor;
use Twig_Environment;
use Twig_NodeInterface;

class TranslationBlock extends BaseVisitor implements \Twig_NodeVisitorInterface
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
