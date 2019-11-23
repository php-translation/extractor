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
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\NodeVisitor\NodeVisitorInterface;
use Twig\Source;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Twig3FileExtractor extends AbstractExtension implements FileExtractor
{
    /**
     * @var Visitor[]|NodeVisitorInterface[]
     */
    private $visitors = [];

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $twig->addExtension($this);
    }

    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection)
    {
        foreach ($this->visitors as $v) {
            $v->init($collection, $file);
        }

        $path = $file->getRelativePath();

        if (class_exists('Twig_Source') || class_exists(Source::class)) {
            // Twig 2.0
            $stream = $this->twig->tokenize(new Source($file->getContents(), $file->getRelativePathname(), $path));
        } else {
            $stream = $this->twig->tokenize($file->getContents(), $path);
        }
        $this->twig->parse($stream);
    }

    public function getType()
    {
        return 'twig';
    }

    /**
     * @param NodeVisitorInterface $visitor
     */
    public function addVisitor(NodeVisitorInterface $visitor)
    {
        $this->visitors[] = $visitor;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return $this->visitors;
    }

    public function getName()
    {
        return 'php.translation';
    }
}
