<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor;

use Doctrine\Common\Annotations\DocParser;
use PhpParser\Node;
use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Annotation\Desc;
use Translation\Extractor\Annotation\Ignore;
use Translation\Extractor\Model\Error;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Model\SourceLocation;

/**
 * Base class for any visitor.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class BaseVisitor implements Visitor
{
    protected $collection;
    protected $file;

    /**
     * @var DocParser
     */
    private $docParser;

    /**
     * {@inheritdoc}
     */
    public function init(SourceCollection $collection, SplFileInfo $file): void
    {
        $this->collection = $collection;
        $this->file = $file;
    }

    protected function getAbsoluteFilePath(): string
    {
        return $this->file->getRealPath();
    }

    protected function addError(Node $node, string $errorMessage): void
    {
        $docComment = $node->getDocComment();
        $file = $this->getAbsoluteFilePath();

        if (property_exists($node, 'value')) {
            $line = $node->value->getAttribute('startLine');
        } else {
            $line = $node->getAttribute('startLine');
        }
        if (null !== $docComment) {
            $context = 'file '.$file.' near line '.$line;
            foreach ($this->getDocParser()->parse($docComment->getText(), $context) as $annotation) {
                if ($annotation instanceof Ignore) {
                    return;
                }
            }
        }

        $this->collection->addError(new Error($errorMessage, $file, $line));
    }

    protected function addLocation(string $text, int $line, Node $node = null, array $context = []): void
    {
        if (null === $location = $this->getLocation($text, $line, $node, $context)) {
            return;
        }

        $this->collection->addLocation($location);
    }

    protected function getLocation(string $text, int $line, Node $node = null, array $context = []): ?SourceLocation
    {
        $file = $this->getAbsoluteFilePath();
        if (null !== $node && null !== $docComment = $node->getDocComment()) {
            $parserContext = 'file '.$file.' near line '.$line;
            foreach ($this->getDocParser()->parse($docComment->getText(), $parserContext) as $annotation) {
                if ($annotation instanceof Ignore) {
                    return null;
                } elseif ($annotation instanceof Desc) {
                    $context['desc'] = $annotation->text;
                }
            }
        }

        return new SourceLocation($text, $file, $line, $context);
    }

    private function getDocParser(): DocParser
    {
        if (null === $this->docParser) {
            $this->docParser = new DocParser();

            $this->docParser->setImports([
                'ignore' => Ignore::class,
                'desc' => Desc::class,
            ]);
            $this->docParser->setIgnoreNotImportedAnnotations(true);
        }

        return $this->docParser;
    }

    public function setDocParser(DocParser $docParser): void
    {
        $this->docParser = $docParser;
    }
}
