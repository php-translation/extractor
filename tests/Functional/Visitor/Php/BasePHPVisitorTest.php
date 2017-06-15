<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\FileExtractor\PHPFileExtractor;
use Symfony\Component\Finder\Finder;
use Translation\Extractor\Model\SourceCollection;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class BasePHPVisitorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $visitor
     * @param $namespaceForTestFile
     *
     * @return SourceCollection
     *
     * @throws \Exception
     */
    protected function getSourceLocations($visitor, $namespaceForTestFile)
    {
        $extractor = new PHPFileExtractor();

        if (is_array($visitor)) {
            foreach($visitor as $nodeVisitor) {
                $extractor->addVisitor($nodeVisitor);
            }
        } else {
            $extractor->addVisitor($visitor);
        }

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
