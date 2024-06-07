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
final class TwigFileExtractor extends AbstractExtension implements FileExtractor
{
    /**
     * @var NodeVisitorInterface[]
     */
    private array $visitors = [];

    public function __construct(private readonly Environment $twig)
    {
        $twig->addExtension($this);
    }

    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection): void
    {
        foreach ($this->visitors as $v) {
            if ($v instanceof Visitor) {
                $v->init($collection, $file);
            }
        }

        $path = $file->getRelativePath();

        $stream = $this->twig->tokenize(new Source($file->getContents(), $file->getRelativePathname(), $path));
        $this->twig->parse($stream);
    }

    public function supportsExtension(string $extension): bool
    {
        return 'twig' === $extension;
    }

    public function addVisitor(NodeVisitorInterface $visitor): void
    {
        $this->visitors[] = $visitor;
    }

    public function getNodeVisitors(): array
    {
        return $this->visitors;
    }

    public function getName(): string
    {
        return 'php.translation';
    }
}
