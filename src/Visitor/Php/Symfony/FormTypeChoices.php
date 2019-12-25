<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Php\Symfony;

use Doctrine\Common\Annotations\DocParser;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Annotation\Ignore;
use Translation\Extractor\Model\SourceLocation;

/**
 * @author Rein Baarsma <rein@solidwebcode.com>
 */
final class FormTypeChoices extends AbstractFormType implements NodeVisitor
{
    use FormTrait;

    /**
     * @var int defaults to major version 3
     */
    protected $symfonyMajorVersion = 3;

    private $variables = [];

    private $state;

    public function setSymfonyMajorVersion(int $sfMajorVersion): void
    {
        $this->symfonyMajorVersion = $sfMajorVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node): ?Node
    {
        if (!$this->isFormType($node)) {
            return null;
        }

        parent::enterNode($node);

        if (null === $this->state && $node instanceof Node\Expr\Assign) {
            $this->state = 'variable';
        } elseif ('variable' === $this->state && $node instanceof Node\Expr\Variable) {
            $this->variables['__variable-name'] = $node->name;
            $this->state = 'value';
        } elseif ('value' === $this->state && $node instanceof Node\Expr\Array_) {
            $this->variables[$this->variables['__variable-name']] = $node;
            $this->state = null;
        } else {
            $this->state = null;
        }

        // symfony 3 or 4 displays key by default, where symfony 2 displays value
        $useKey = 2 !== $this->symfonyMajorVersion;

        // remember choices in this node
        $choicesNodes = [];

        // loop through array
        if (!$node instanceof Node\Expr\Array_) {
            return null;
        }

        $domain = null;
        foreach ($node->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            if ('choices_as_values' === $item->key->value) {
                $useKey = true;

                continue;
            }

            if ('choice_translation_domain' === $item->key->value) {
                if ($item->value instanceof Node\Scalar\String_) {
                    $domain = $item->value->value;
                } elseif ($item->value instanceof Node\Expr\ConstFetch && 'false' === $item->value->name->toString()) {
                    $domain = false;
                }

                continue;
            }

            if ('choices' !== $item->key->value) {
                continue;
            }

            //do not parse choices if the @Ignore annotation is attached
            if ($this->isIgnored($item->key)) {
                continue;
            }

            $choicesNodes[] = $item->value;
        }

        if (0 === \count($choicesNodes) || false === $domain) {
            return null;
        }

        // probably will be only 1, but who knows
        foreach ($choicesNodes as $choices) {
            if ($choices instanceof Node\Expr\Variable && isset($this->variables[$choices->name])) {
                $choices = $this->variables[$choices->name];
            } elseif (!$choices instanceof Node\Expr\Array_) {
                $this->addError($choices, 'Form choice is not an array');

                continue;
            }

            foreach ($choices->items as $citem) {
                $labelNode = $useKey ? $citem->key : $citem->value;
                if (!$labelNode instanceof Node\Scalar\String_) {
                    $this->addError($citem, 'Choice label is not a scalar string');

                    continue;
                }

                $this->lateCollect(new SourceLocation($labelNode->value, $this->getAbsoluteFilePath(), $choices->getAttribute('startLine'), ['domain' => $domain]));
            }
        }

        return null;
    }

    protected function isIgnored(Node $node): bool
    {
        //because of getDocParser method is private, we have to create a new custom instance
        $docParser = new DocParser();
        $docParser->setImports([
            'ignore' => Ignore::class,
        ]);
        $docParser->setIgnoreNotImportedAnnotations(true);
        if (null !== $docComment = $node->getDocComment()) {
            foreach ($docParser->parse($docComment->getText()) as $annotation) {
                if ($annotation instanceof Ignore) {
                    return true;
                }
            }
        }

        return false;
    }
}
