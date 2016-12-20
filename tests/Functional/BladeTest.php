<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional;

use Symfony\Component\Finder\Finder;
use Translation\Extractor\FileExtractor\BladeFileExtractor;
use Translation\Extractor\Model\SourceCollection;

class BladeTest extends \PHPUnit_Framework_TestCase
{
    private function getSourceLocations($relativePath)
    {
        $extractor = new BladeFileExtractor();

        $filename = substr($relativePath, 1 + strrpos($relativePath, '/'));
        $path = __DIR__.'/../Resources/'.substr($relativePath, 0, strrpos($relativePath, '/'));

        $finder = new Finder();
        $finder->files()->name($filename)->in($path);
        $collection = new SourceCollection();
        foreach ($finder as $file) {
            $extractor->getSourceLocations($file, $collection);
        }

        return $collection;
    }

    public function testExtractLang()
    {
        $collection = $this->getSourceLocations('Blade/lang.blade.php');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foo.bar', $source->getMessage());
    }

    public function testExtractTrans()
    {
        $collection = $this->getSourceLocations('Blade/trans.blade.php');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foo.bar', $source->getMessage());
    }

    public function testExtractTransChoice()
    {
        $collection = $this->getSourceLocations('Blade/trans_choice.blade.php');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foo.bar', $source->getMessage());
    }
}
