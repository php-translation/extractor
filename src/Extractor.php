<?php

namespace Translation\Extractor;

use Symfony\Component\Finder\Finder;
use Translation\Extractor\FileExtractor\FileExtractor;
use Translation\Extractor\Model\SourceCollection;

class Extractor
{
    /**
     * @var FileExtractor[]
     */
    private $fileExtractors;

    /**
     * @param string $dir
     *
     * @return SourceCollection
     */
    public function extract($dir)
    {
        $collection = new SourceCollection();
        $finder = new Finder();
        $finder->files()->in($dir);

        foreach ($finder as $file) {
            $type = $this->getTypeFromExtension($file->getExtension());
            foreach ($this->fileExtractors as $extractor) {
                if ($extractor->getType() !== $type) {
                    continue;
                }

                $extractor->getSourceLocations($file, $collection);
            }
        }

        return $collection;
    }

    private function getTypeFromExtension($ext)
    {
        switch ($ext) {
            case 'php':
            case 'php5':
                return 'php';
            case 'twig':
                return 'twig';
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
}
