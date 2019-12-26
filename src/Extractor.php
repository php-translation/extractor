<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\FileExtractor\FileExtractor;
use Translation\Extractor\Model\SourceCollection;

/**
 * Main class for all extractors. This is the service that will be loaded with file
 * extractors.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Extractor
{
    /**
     * @var FileExtractor[]
     */
    private $fileExtractors = [];

    public function extract(Finder $finder): SourceCollection
    {
        return $this->doExtract($finder);
    }

    public function extractFromDirectory(string $dir): SourceCollection
    {
        $finder = new Finder();
        $finder->files()->in($dir);

        return $this->doExtract($finder);
    }

    private function getType(SplFileInfo $file): string
    {
        $filename = $file->getFilename();
        if (preg_match('|.+\.blade\.php$|', $filename)) {
            $ext = 'blade.php';
        } else {
            $ext = $file->getExtension();
        }

        switch ($ext) {
            case 'php':
            case 'php5':
            case 'phtml':
                return 'php';
            case 'twig':
                return 'twig';
            case 'blade.php':
                return 'blade';
            default:
                return $ext;
        }
    }

    public function addFileExtractor(FileExtractor $fileExtractor): void
    {
        $this->fileExtractors[] = $fileExtractor;
    }

    private function doExtract(Finder $finder): SourceCollection
    {
        $collection = new SourceCollection();
        foreach ($finder as $file) {
            $type = $this->getType($file);
            foreach ($this->fileExtractors as $extractor) {
                if (in_array($type, $extractor->getTypes())) {
                    $extractor->getSourceLocations($file, $collection);
                }
            }
        }

        return $collection;
    }
}
