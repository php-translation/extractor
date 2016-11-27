<?php

namespace Translation\Extractor\FileExtractor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class TwigFileExtractor implements FileExtractor
{
    /**
     * @var Visitor[]|\Twig_NodeVisitorInterface[]
     */
    private $visitors;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig = null)
    {
        $this->twig = $twig ?: new \Twig_Environment();
    }

    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection)
    {
        foreach ($this->visitors as $v) {
            $v->init($collection, $file);
        }

        $path = $file->getRelativePath();

        $tokens = $this->twig->parse($this->twig->tokenize($file->getContents(), $path));
        $traverser = new \Twig_NodeTraverser($this->twig, $this->visitors);
        $traverser->traverse($tokens);
    }

    public function getType()
    {
        return 'twig';
    }

    /**
     * @param \Twig_NodeVisitorInterface $visitor
     */
    public function addVisitor(\Twig_NodeVisitorInterface $visitor)
    {
        $this->visitors[] = $visitor;
    }
}
