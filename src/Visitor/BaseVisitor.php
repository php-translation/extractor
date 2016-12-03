<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;

abstract class BaseVisitor implements Visitor
{
    /**
     * @var SourceCollection
     */
    protected $collection;

    /**
     * @var SplFileInfo
     */
    protected $file;

    public function init(SourceCollection $collection, SplFileInfo $file)
    {
        $this->collection = $collection;
        $this->file = $file;
    }

    protected function getAbsoluteFilePath()
    {
        return $this->file->getRealPath();
    }
}
