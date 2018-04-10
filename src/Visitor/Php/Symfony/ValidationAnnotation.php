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

use Doctrine\Common\Annotations\AnnotationException;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ValidationAnnotation extends BasePHPVisitor implements NodeVisitor
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var string
     */
    private $namespace;

    /**
     * ValidationExtractor constructor.
     *
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->namespace = '';
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            if (isset($node->name)) {
                // save the namespace
                $this->namespace = implode('\\', $node->name->parts);
            }

            return;
        }

        if (!$node instanceof Node\Stmt\Class_) {
            return;
        }

        $name = '' === $this->namespace ? $node->name : $this->namespace.'\\'.$node->name;

        if (!class_exists($name)) {
            return;
        }

        try {
            /** @var ClassMetadata $metadata */
            $metadata = $this->metadataFactory->getMetadataFor($name);
        } catch (AnnotationException $e) {
            $this->addError($node, sprintf('Could not parse class "%s" for annotations. %s', $this->namespace, $e->getMessage()));

            return;
        }

        if (!$metadata->hasConstraints() && !count($metadata->getConstrainedProperties())) {
            return;
        }

        $this->extractFromConstraints($metadata->constraints);
        foreach ($metadata->members as $members) {
            foreach ($members as $member) {
                $this->extractFromConstraints($member->constraints);
            }
        }
    }

    /**
     * @param array $constraints
     */
    private function extractFromConstraints(array $constraints)
    {
        foreach ($constraints as $constraint) {
            $ref = new \ReflectionClass($constraint);
            $defaultValues = $ref->getDefaultProperties();

            $properties = $ref->getProperties();

            foreach ($properties as $property) {
                $propName = $property->getName();

                // If the property ends with 'Message'
                if ('message' === strtolower(substr($propName, -1 * strlen('Message')))) {
                    // If it is different from the default value
                    if ($defaultValues[$propName] !== $constraint->{$propName}) {
                        $this->addLocation($constraint->{$propName}, 0, null, ['domain' => 'validators']);
                    }
                }
            }
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
