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

    /**
     * @param Finder $finder
     *
     * @return SourceCollection
     */
    public function extract(Finder $finder)
    {
        return $this->doExtract($finder);
    }

    /**
     * @param string $dir
     *
     * @return SourceCollection
     */
    public function extractFromDirectory($dir)
    {
        $finder = new Finder();
        $finder->files()->in($dir);

        return $this->doExtract($finder);
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    private function getType(SplFileInfo $file)
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

    /**
     * @param FileExtractor $fileExtractors
     */
    public function addFileExtractor(FileExtractor $fileExtractor)
    {
        $this->fileExtractors[] = $fileExtractor;
    }

    /**
     * @param Finder $finder
     *
     * @return SourceCollection
     */
    private function doExtract(Finder $finder)
    {
        $collection = new SourceCollection();
        foreach ($finder as $file) {
            $type = $this->getType($file);
            foreach ($this->fileExtractors as $extractor) {
                if ($extractor->getType() !== $type) {
                    continue;
                }

                $extractor->getSourceLocations($file, $collection);
            }
        }

        return $collection;
    }
}
