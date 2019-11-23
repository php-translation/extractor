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
use Twig\Node\Node;

/**
 * Factory class and base class for TwigVisitor.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class TwigVisitor extends BaseVisitor
{
    /**
     * @var Worker|LegacyWorker
     */
    private $worker;

    /**
     * @param Worker|LegacyWorker|null $worker
     */
    public function __construct($worker = null)
    {
        if (null === $worker) {
            $worker = WorkerFactory::create();
        }

        $this->worker = $worker;
    }

    /**
     * @return TwigVisitor
     *
     * @deprecated since 1.2. Will be removed in 2.0. Use TwigVisitorFactory instead.
     */
    public static function create()
    {
        return TwigVisitorFactory::create();
    }

    /**
     * @param \Twig_Node|\Twig_NodeInterface|Node $node
     *
     * @return \Twig_Node|\Twig_NodeInterface|Node
     */
    protected function doEnterNode($node)
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
}
