<?php

namespace Translation\Extractor\Visitor\Twig;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Visitor;
use Twig_Environment;
use Twig_NodeInterface;

class TranslationFilter implements Visitor, \Twig_NodeVisitorInterface
{
    public function enterNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        // TODO: Implement enterNode() method.
    }

    public function leaveNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        // TODO: Implement leaveNode() method.
    }

    public function getPriority()
    {
        // TODO: Implement getPriority() method.
    }

    public function init(SourceCollection $collection, SplFileInfo $file)
    {
        // TODO: Implement init() method.
    }
}
