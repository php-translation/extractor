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

/**
 * Factory class and base class for TwigVisitor.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class TwigVisitor extends BaseVisitor
{
    /**
     * @var Worker
     */
    private $worker;

    /**
     * @param Worker|null $worker
     */
    public function __construct(Worker $worker = null)
    {
        if (null === $worker) {
            $worker = new Worker();
        }

        $this->worker = $worker;
    }

    /**
     * @return Twig1Visitor|Twig2Visitor
     *
     * @deprecated since 1.2. Will be removed in 2.0. Use TwigVisitorFactory instead.
     */
    public static function create()
    {
        if (-1 === version_compare(\Twig_Environment::VERSION, '2.0')) {
            return new Twig1Visitor();
        }

        return new Twig2Visitor();
    }

    /**
     * @param \Twig_Node|\Twig_NodeInterface $node
     *
     * @return \Twig_Node|\Twig_NodeInterface
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
