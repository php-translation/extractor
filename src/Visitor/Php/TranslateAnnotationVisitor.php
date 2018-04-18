<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Php;

use Doctrine\Common\Annotations\DocParser;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Annotation\Translate;

/**
 * Class TranslationAnnotationVisitor.
 *
 * Supports using @Translate annotation for marking string nodes to be added to the dictionary
 */
class TranslateAnnotationVisitor extends BasePHPVisitor implements NodeVisitor
{
    /** @var DocParser */
    protected $translateDocParser;

    /**
     * @return DocParser
     */
    private function getTranslateDocParser()
    {
        if (null === $this->translateDocParser) {
            $this->translateDocParser = new DocParser();

            $this->translateDocParser->setImports([
                'translate' => Translate::class,
            ]);
            $this->translateDocParser->setIgnoreNotImportedAnnotations(true);
        }

        return $this->translateDocParser;
    }

    public function enterNode(Node $node)
    {
        // look for strings
        if (!$node instanceof Node\Scalar\String_) {
            return null;
        }

        //look for string with comment
        $comments = $node->getAttribute('comments', []);
        if (!count($comments)) {
            return null;
        }

        foreach ($comments as $comment) {
            if (!$comment instanceof Comment\Doc) {
                return null;
            }

            foreach ($this->getTranslateDocParser()->parse($comment->getText()) as $annotation) {
                //add phrase to dictionary
                $this->addLocation($node->value, $node->getAttribute('startLine'), $node, ['domain' => $annotation->getDomain()]);

                break;
            }
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function beforeTraverse(array $nodes)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
