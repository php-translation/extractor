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
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

/**
 * @author Luca Passini <lucapassini@gmail.com>
 */
final class Constraint extends BasePHPVisitor implements NodeVisitor
{
    public const VALIDATORS_DOMAIN = 'validators';

    public const CONSTRAINT_CLASS_NAMES = [
        'AbstractComparison',
        'All',
        'Bic',
        'Blank',
        'Callback',
        'CardScheme',
        'Choice',
        'Collection',
        'Composite',
        'Count',
        'Country',
        'Currency',
        'Date',
        'DateTime',
        'DisableAutoMapping',
        'DivisibleBy',
        'Email',
        'EnableAutoMapping',
        'EqualTo',
        'Existence',
        'Expression',
        'File',
        'GreaterThan',
        'GreaterThanOrEqual',
        'GroupSequence',
        'GroupSequenceProvider',
        'Iban',
        'IdenticalTo',
        'Image',
        'Ip',
        'Isbn',
        'IsFalse',
        'IsNull',
        'Issn',
        'IsTrue',
        'Json',
        'Language',
        'Length',
        'LessThan',
        'LessThanOrEqual',
        'Locale',
        'Luhn',
        'Negative',
        'NegativeOrZero',
        'NotBlank',
        'NotCompromisedPassword',
        'NotEqualTo',
        'NotIdenticalTo',
        'NotNull',
        'NumberConstraintTrait',
        'Optional',
        'Positive',
        'PositiveOrZero',
        'Range',
        'Regex',
        'Required',
        'Time',
        'Timezone',
        'Traverse',
        'Type',
        'Unique',
        'Url',
        'Uuid',
        'Valid',
    ];

    public function beforeTraverse(array $nodes): ?Node
    {
        return null;
    }

    public function enterNode(Node $node): ?Node
    {
        if (!$node instanceof Node\Expr\New_) {
            return null;
        }

        $className = $node->class;
        if (!$className instanceof Node\Name) {
            return null;
        }

        $parts = $className->getParts();
        $isConstraintClass = false;

        // we need to check every part since `Assert\NotBlank` would be split in 2 different pieces
        foreach ($parts as $part) {
            if (\in_array($part, self::CONSTRAINT_CLASS_NAMES)) {
                $isConstraintClass = true;

                break;
            }
        }

        // unsupported class
        if (!$isConstraintClass) {
            return null;
        }

        $args = $node->args;
        if (0 === \count($args)) {
            return null;
        }

        $arg = $args[0];
        if (!$arg instanceof Node\Arg) {
            return null;
        }

        $options = $arg->value;
        if (!$options instanceof Node\Expr\Array_) {
            return null;
        }

        $message = null;
        $messageNode = null;

        foreach ($options->items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            // there could be false positives, but it should catch most of the useful properties
            // (e.g. `message`, `minMessage`)
            if (false === stripos($item->key->value, 'message')) {
                continue;
            }

            if (!$item->value instanceof Node\Scalar\String_) {
                $this->addError($item, 'Constraint message is not a scalar string');

                continue;
            }

            $message = $item->value->value;
            $messageNode = $item;

            break;
        }

        if (!empty($message) && null !== $messageNode) {
            $this->addLocation($message, $messageNode->getAttribute('startLine'), $messageNode, [
                'domain' => self::VALIDATORS_DOMAIN,
            ]);
        }

        return null;
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
