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

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Visitor\Visitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class PHPFileExtractor implements FileExtractor
{
    /**
     * @var Visitor[]|NodeVisitor[]
     */
    private $visitors = [];

    /**
     * {@inheritdoc}
     */
    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection): void
    {
        $path = $file->getRelativePath();
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        foreach ($this->visitors as $v) {
            $v->init($collection, $file);
            $traverser->addVisitor($v);
        }

        try {
            $tokens = $parser->parse($file->getContents());
            $traverser->traverse($tokens);
        } catch (Error $e) {
            trigger_error(sprintf('Skipping file "%s" because of parse Error: %s. ', $path, $e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsExtension(string $extension): bool
    {
        return \in_array($extension, ['php', 'php5', 'phtml']);
    }

    public function addVisitor(NodeVisitor $visitor): void
    {
        $this->visitors[] = $visitor;
    }
}
