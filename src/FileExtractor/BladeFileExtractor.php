<?php

namespace Translation\Extractor\FileExtractor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Visitor\Visitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class BladeFileExtractor implements FileExtractor
{
    /**
     * @var Visitor[]
     */
    private $visitors = [];

    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection)
    {
        foreach ($this->visitors as $v) {
            $v->init($collection, $file);
        }

        $path = $file->getRelativePath();

        // TODO
    }

    public function getType()
    {
        return 'blade';
    }

    /**
     * @param $visitor
     */
    public function addVisitor($visitor)
    {
        // TODO
        $this->visitors[] = $visitor;
    }
}
