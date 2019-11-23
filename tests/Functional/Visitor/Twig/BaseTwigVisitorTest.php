<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional\Visitor\Twig;

use PHPUnit\Framework\TestCase;
use Translation\Extractor\FileExtractor\Twig3FileExtractor;
use Translation\Extractor\FileExtractor\TwigFileExtractor;
use Symfony\Component\Finder\Finder;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Visitor\Twig\LegacyWorker;
use Translation\Extractor\Visitor\Twig\Worker;
use Twig\Environment;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class BaseTwigVisitorTest extends TestCase
{
    protected function getSourceLocations($visitor, $relativePath)
    {
        $extractor = $this->getExtractor();
        $extractor->addVisitor($visitor);

        $filename = substr($relativePath, 1 + strrpos($relativePath, '/'));
        $path = __DIR__.'/../../../Resources/'.substr($relativePath, 0, strrpos($relativePath, '/'));

        $finder = new Finder();
        $finder->files()->name($filename)->in($path);
        $collection = new SourceCollection();
        foreach ($finder as $file) {
            $extractor->getSourceLocations($file, $collection);
        }

        return $collection;
    }

    /**
     * @return TwigFileExtractor|Twig3FileExtractor
     */
    private function getExtractor()
    {
        if(-1 === version_compare(Environment::VERSION, '3.0')) {
            return new TwigFileExtractor(TwigEnvironmentFactory::create());
        }

        return new Twig3FileExtractor(TwigEnvironmentFactory::create());
    }
}
