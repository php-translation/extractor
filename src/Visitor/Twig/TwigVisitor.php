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
    private $worker;

    public function __construct(Worker $worker = null)
    {
        if (null === $worker) {
            $worker = new Worker();
        }

        $this->worker = $worker;
    }

    protected function doEnterNode(Node $node): Node
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
