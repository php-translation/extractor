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
    /**
     * @var SourceCollection
     */
    protected $collection;

    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var DocParser
     */
    private $docParser;

    public function init(SourceCollection $collection, SplFileInfo $file)
    {
        $this->collection = $collection;
        $this->file = $file;
    }

    protected function getAbsoluteFilePath()
    {
        return $this->file->getRealPath();
    }

    /**
     * @param Node   $node
     * @param string $errorMessage
     */
    protected function addError(Node $node, $errorMessage)
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

    /**
     * @param string    $text
     * @param int       $line
     * @param Node|null $node
     * @param array     $context
     */
    protected function addLocation($text, $line, Node $node = null, array $context = [])
    {
        if (null === $location = $this->getLocation($text, $line, $node, $context)) {
            return;
        }

        $this->collection->addLocation($location);
    }

    /**
     * @param string    $text
     * @param int       $line
     * @param Node|null $node
     * @param array     $context
     *
     * @return SourceLocation|null
     */
    protected function getLocation($text, $line, Node $node = null, array $context = [])
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

    /**
     * @return DocParser
     */
    private function getDocParser()
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

    /**
     * @param DocParser $docParser
     */
    public function setDocParser(DocParser $docParser)
    {
        $this->docParser = $docParser;
    }
}
