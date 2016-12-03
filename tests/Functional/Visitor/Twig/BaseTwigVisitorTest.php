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

use Translation\Extractor\FileExtractor\TwigFileExtractor;
use Symfony\Component\Finder\Finder;
use Translation\Extractor\Model\SourceCollection;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

abstract class BaseTwigVisitorTest extends \PHPUnit_Framework_TestCase
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
     * @return TwigFileExtractor
     */
    private function getExtractor()
    {
        $env = new \Twig_Environment();
        $env->addExtension(new TranslationExtension($translator = new IdentityTranslator(new MessageSelector())));
        $env->setLoader(new \Twig_Loader_String());

        return new TwigFileExtractor($env);
    }
}
