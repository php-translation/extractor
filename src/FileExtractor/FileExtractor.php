<?php

namespace Translation\Extractor\FileExtractor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;

/**
 * Extract SourceLocations form a file.
 */
interface FileExtractor
{
    /**
     * @param SplFileInfo $file
     */
    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection);

    /**
     * The file type the extractor supports.
     *
     * @return string
     */
    public function getType();
}
