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
use Translation\Extractor\Annotation\Ignore;
use Translation\Extractor\Model\Error;
use Translation\Extractor\Model\SourceCollection;

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
    protected function addError(Node $node, string $errorMessage)
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
     * @return DocParser
     */
    private function getDocParser(): DocParser
    {
        if (null === $this->docParser) {
            $this->docParser = new DocParser();

            $this->docParser->setImports([
                'ignore' => Ignore::class,
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
