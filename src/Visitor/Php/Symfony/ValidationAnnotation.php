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

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\Validator\MetadataFactoryInterface as LegacyMetadataFactoryInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ValidationAnnotation extends BasePHPVisitor implements NodeVisitor
{
    /**
     * @var MetadataFactoryInterface|LegacyMetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var string
     */
    private $namespace;

    /**
     * ValidationExtractor constructor.
     *
     * @param MetadataFactoryInterface|LegacyMetadataFactoryInterface $metadataFactory
     */
    public function __construct($metadataFactory)
    {
        if (!(
            $metadataFactory instanceof MetadataFactoryInterface
            || $metadataFactory instanceof LegacyMetadataFactoryInterface
        )) {
            throw new \InvalidArgumentException(sprintf('%s expects an instance of MetadataFactoryInterface', get_class($this)));
        }
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

        $metadata = ($this->metadataFactory instanceof ClassMetadataFactoryInterface) ? $this->metadataFactory->getClassMetadata($name) : $this->metadataFactory->getMetadataFor($name);
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
                if (strtolower(substr($propName, -1 * strlen('Message'))) === 'message') {
                    // If it is different from the default value
                    if ($defaultValues[$propName] !== $constraint->{$propName}) {
                        $this->collection->addLocation(new SourceLocation($constraint->{$propName}, $this->getAbsoluteFilePath(), 0, ['domain' => 'validators']));
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
