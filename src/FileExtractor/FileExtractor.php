<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\FileExtractor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;

/**
 * Extract SourceLocations form a file.
 */
interface FileExtractor
{
    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection): void;

    public function supportsExtension(string $extension): bool;
}
