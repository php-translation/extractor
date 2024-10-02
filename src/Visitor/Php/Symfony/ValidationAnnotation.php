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
use Symfony\Component\Validator\Exception\NoSuchMetadataException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ValidationAnnotation extends BasePHPVisitor implements NodeVisitor
{
    private MetadataFactoryInterface $metadataFactory;

    private string $namespace;

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function beforeTraverse(array $nodes): ?Node
    {
        $this->namespace = '';

        return null;
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            if (isset($node->name)) {
                // save the namespace
                $this->namespace = implode('\\', $node->name->getParts());
            }

            return null;
        }

        if (!$node instanceof Node\Stmt\Class_) {
            return null;
        }

        $name = '' === $this->namespace ? $node->name : $this->namespace.'\\'.$node->name;

        if (!class_exists($name)) {
            return null;
        }

        try {
            /** @var ClassMetadata $metadata */
            $metadata = $this->metadataFactory->getMetadataFor($name);
        } catch (NoSuchMetadataException $e) {
            $this->addError($node, sprintf('Could not parse class "%s" for annotations. %s', $this->namespace, $e->getMessage()));

            return null;
        }

        if (!$metadata->hasConstraints() && !\count($metadata->getConstrainedProperties())) {
            return null;
        }

        $this->extractFromConstraints($metadata->constraints);
        foreach ($metadata->members as $members) {
            foreach ($members as $member) {
                $this->extractFromConstraints($member->constraints);
            }
        }

        return null;
    }

    private function extractFromConstraints(array $constraints): void
    {
        foreach ($constraints as $constraint) {
            $ref = new \ReflectionClass($constraint);
            $defaultValues = $ref->getDefaultProperties();

            $properties = $ref->getProperties();

            foreach ($properties as $property) {
                $propName = $property->getName();

                // If the property ends with 'Message'
                if ('message' === strtolower(substr($propName, -1 * \strlen('Message')))) {
                    // If it is different from the default value
                    if ($defaultValues[$propName] !== $constraint->{$propName}) {
                        $this->addLocation($constraint->{$propName}, 0, null, ['domain' => 'validators']);
                    }
                }
            }
        }
    }

    public function leaveNode(Node $node): ?Node
    {
        return null;
    }

    public function afterTraverse(array $nodes): ?Node
    {
        return null;
    }
}
