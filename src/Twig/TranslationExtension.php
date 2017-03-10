<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Twig;

class TranslationExtension extends \Twig_Extension
{
    /**
     * @var \Twig_NodeVisitorInterface[]
     */
    private $visitors;

    /**
     * @param \Twig_NodeVisitorInterface[] $visitors
     *
     */
    public function addVisitor(\Twig_NodeVisitorInterface $visitor)
    {
        $this->visitors[] = $visitor;
    }

    public function getNodeVisitors()
    {
        return $this->visitors;
    }
}
