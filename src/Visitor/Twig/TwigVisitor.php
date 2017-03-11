<?php

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
     *
     * @param Worker|null $worker
     */
    public function __construct(Worker $worker = null)
    {
        if(!$worker) {
            $worker = new Worker();
        }

        $this->worker = $worker;
    }

    /**
     * @return Twig1Visitor|Twig2Visitor
     */
    public static function create()
    {
        if (\Twig_Environment::MAJOR_VERSION === 1) {
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
        return $this->worker->work($node, $this->collection, function () {
            return $this->getAbsoluteFilePath();
        });
    }
}
