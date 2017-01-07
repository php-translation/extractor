<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\FileExtractor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Visitor\Visitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TwigFileExtractor implements FileExtractor
{
    /**
     * @var Visitor[]|\Twig_NodeVisitorInterface[]
     */
    private $visitors = [];

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection)
    {
        foreach ($this->visitors as $v) {
            $v->init($collection, $file);
        }

        $path = $file->getRelativePath();

        if (class_exists('Twig_Source')) {
            // Twig 2.0
            $stream = $this->twig->tokenize(new \Twig_Source($file->getContents(), $file->getRelativePathname(), $path));
        } else {
            $stream = $this->twig->tokenize($file->getContents(), $path);
        }
        $tokens = $this->twig->parse($stream);
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
