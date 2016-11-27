<?php

namespace Translation\Extractor\Tests\Functional\Visitor;

use Symfony\Component\Finder\Finder;
use Translation\Extractor\FileExtractor\FileExtractor;
use Translation\Extractor\Model\SourceCollection;

abstract class BaseVisitorTest extends \PHPUnit_Framework_TestCase
{
    protected function getSourceLocations($visitor, $namespaceForTestFile)
    {
        $extractor = $this->getExtractor();
        $extractor->addVisitor($visitor);

        $currentNamespace = explode('\\', __NAMESPACE__);
        $fileNamespace = explode('\\', $namespaceForTestFile);
        $filename = array_pop($fileNamespace).'*';

        $path = __DIR__.'/../..';
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
        $collection = new SourceCollection();
        foreach ($finder as $file) {
            $extractor->getSourceLocations($file, $collection);
        }

        return $collection;
    }

    /**
     * @return FileExtractor
     */
    abstract protected function getExtractor();
}
