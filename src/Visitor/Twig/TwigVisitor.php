<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Twig;

use Translation\Extractor\Visitor\BaseVisitor;
use Twig\Environment;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TwigVisitor extends BaseVisitor implements NodeVisitorInterface
{
    private $worker;

    public function __construct(Worker $worker = null)
    {
        if (null === $worker) {
            $worker = new Worker();
        }

        $this->worker = $worker;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node, Environment $env): Node
    {
        // If not initialized
        if (null === $this->collection) {
            // We have not executed BaseVisitor::init which means that we are not currently extracting
            return $node;
        }

        return $this->worker->work($node, $this->collection, function () {
            return $this->getAbsoluteFilePath();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node, Environment $env): ?Node
    {
        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
