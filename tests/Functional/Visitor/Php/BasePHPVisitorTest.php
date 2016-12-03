<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\FileExtractor\PHPFileExtractor;
use Symfony\Component\Finder\Finder;
use Translation\Extractor\Model\SourceCollection;

abstract class BasePHPVisitorTest extends \PHPUnit_Framework_TestCase
{
    protected function getSourceLocations($visitor, $namespaceForTestFile)
    {
        $extractor = new PHPFileExtractor();
        $extractor->addVisitor($visitor);

        $currentNamespace = explode('\\', __NAMESPACE__);
        $fileNamespace = explode('\\', $namespaceForTestFile);
        $filename = array_pop($fileNamespace).'*';

        $path = __DIR__.'/../../..';
        foreach ($fileNamespace as $i => $part) {
            if ($currentNamespace[$i] !== $part) {
                // Assert: The namespaces is different now
                for ($j = $i; $j < count($fileNamespace); ++$j) {
                    $path .= '/'.$fileNamespace[$j];
                }
                break;
            }
        }

        $finder = new Finder();
        $finder->files()->name($filename)->in($path);

        if ($finder->count() === 0) {
            throw new \Exception("Cannot find file for: $namespaceForTestFile. Tried path: $path - Maybe filename doesn't match the class?");
        }

        $collection = new SourceCollection();
        foreach ($finder as $file) {
            $extractor->getSourceLocations($file, $collection);
        }

        return $collection;
    }
}
