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

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use PHPStan\PhpDocParser\Ast\ConstExpr\DoctrineConstExprStringNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineTagValueNode;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Translation\Extractor\Annotation\Translate;

/**
 * Class TranslationAnnotationVisitor.
 *
 * Supports using @Translate annotation for marking string nodes to be added to the dictionary
 */
class TranslateAnnotationVisitor extends BasePHPVisitor implements NodeVisitor
{
    public function enterNode(Node $node): ?Node
    {
        // look for strings
        if (!$node instanceof Node\Scalar\String_) {
            return null;
        }

        // look for string with comment
        $comments = $node->getAttribute('comments', []);
        if (!\count($comments)) {
            return null;
        }

        foreach ($comments as $comment) {
            if (!$comment instanceof Comment\Doc) {
                return null;
            }

            $phpDocNode = $this->getPhpDocParser()->parse(
                new TokenIterator($this->lexer->tokenize($comment->getText()))
            );

            $translateTags = $phpDocNode->getTagsByName('@Translate');
            if ([] !== $translateTags) {
                $domain = 'messages';
                if ($translateTags[0]->value instanceof DoctrineTagValueNode) {
                    foreach ($translateTags[0]->value->annotation->arguments as $argument) {
                        if ('domain' === $argument->key->name) {
                            $domain = DoctrineConstExprStringNode::unescape($argument->value);
                        }
                    }
                }
                $this->addLocation($node->value, $node->getAttribute('startLine'), $node, ['domain' => $domain]);
                break;
            }
        }

        return null;
    }

    public function leaveNode(Node $node): ?Node
    {
        return null;
    }

    public function beforeTraverse(array $nodes): ?Node
    {
        return null;
    }

    public function afterTraverse(array $nodes): ?Node
    {
        return null;
    }
}
