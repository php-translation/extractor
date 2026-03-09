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

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\ConstExpr\DoctrineConstExprStringNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use Symfony\Component\Finder\SplFileInfo;
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
    protected ?Lexer $lexer = null;
    protected ?PhpDocParser $phpDocParser = null;

    protected ?SourceCollection $collection = null;
    protected SplFileInfo $file;

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
            $phpDocNode = $this->getPhpDocParser()->parse(
                new TokenIterator($this->lexer->tokenize($docComment->getText()))
            );
            foreach ($phpDocNode->getTags() as $tag) {
                if ('@Ignore' === $tag->name) {
                    return;
                }
            }
        }

        $this->collection->addError(new Error($errorMessage, $file, $line));
    }

    protected function addLocation(string $text, int $line, ?Node $node = null, array $context = []): void
    {
        if (null === $location = $this->getLocation($text, $line, $node, $context)) {
            return;
        }

        $this->collection->addLocation($location);
    }

    protected function getLocation(string $text, int $line, ?Node $node = null, array $context = []): ?SourceLocation
    {
        $file = $this->getAbsoluteFilePath();
        if (null !== $node && null !== $docComment = $node->getDocComment()) {
            $phpDocNode = $this->getPhpDocParser()->parse(
                new TokenIterator($this->lexer->tokenize($docComment->getText()))
            );
            foreach ($phpDocNode->getTags() as $tag) {
                if ('@Ignore' === $tag->name) {
                    return null;
                } elseif ('@Desc' === $tag->name && $tag->value instanceof DoctrineTagValueNode) {
                    if ([] !== $tag->value->annotation->arguments) {
                        $context['desc'] = DoctrineConstExprStringNode::unescape($tag->value->annotation->arguments[0]->value);
                    }
                }
            }
        }

        return new SourceLocation($text, $file, $line, $context);
    }

    protected function getPhpDocParser(): PhpDocParser
    {
        if (null === $this->phpDocParser) {
            $config = new ParserConfig(usedAttributes: []);
            $this->lexer = new Lexer($config);
            $constExprParser = new ConstExprParser($config);
            $typeParser = new TypeParser($config, $constExprParser);
            $this->phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
        }

        return $this->phpDocParser;
    }

    public function setPhpDocParser(PhpDocParser $phpDocParser): void
    {
        $this->phpDocParser = $phpDocParser;
    }
}
