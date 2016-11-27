<?php

namespace Translation\Extractor\Visitor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;

interface Visitor
{
    /**
     * @param SourceCollection $collection
     * @param SplFileInfo      $file
     */
    public function init(SourceCollection $collection, SplFileInfo $file);
}
