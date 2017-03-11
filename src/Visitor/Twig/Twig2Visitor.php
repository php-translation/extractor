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
use Twig_Environment;
use Twig_Node;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Twig2Visitor extends BaseVisitor implements \Twig_NodeVisitorInterface
{
    /**
     * @var WorkerTranslationFilter
     */
    private $worker;

    public function __construct()
    {
        $this->worker = new Worker();
    }

    public function enterNode(Twig_Node $node, Twig_Environment $env)
    {
        return $this->worker->work($node, $this->collection, function () {
            return $this->getAbsoluteFilePath();
        });
    }

    public function leaveNode(Twig_Node $node, Twig_Environment $env)
    {
        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
