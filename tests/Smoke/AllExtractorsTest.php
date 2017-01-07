<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Smoke;

use Symfony\Component\Finder\Finder;
use Translation\Extractor\Extractor;
use Translation\Extractor\FileExtractor\PHPFileExtractor;
use Translation\Extractor\FileExtractor\TwigFileExtractor;
use Translation\Extractor\Tests\Functional\Visitor\Twig\TwigEnvironmentFactory;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTrans;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTransChoice;
use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeChoices;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelImplicit;
use Translation\Extractor\Visitor\Twig\TranslationBlock;
use Translation\Extractor\Visitor\Twig\TranslationFilter;
use Translation\Extractor\Visitor\Twig\Twig2TranslationBlock;
use Translation\Extractor\Visitor\Twig\Twig2TranslationFilter;

/**
 * Smoke test to make sure no extractor throws exceptions.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class AllExtractorsTest extends \PHPUnit_Framework_TestCase
{
    public function testNoException()
    {
        $extractor = new Extractor();
        $extractor->addFileExtractor($this->getPHPFileExtractor());
        $extractor->addFileExtractor($this->getTwigFileExtractor());

        $finder = new Finder();
        $finder->in(dirname(__DIR__));

        $extractor->extract($finder);
    }

    /**
     * @return PHPFileExtractor
     */
    private function getPHPFileExtractor()
    {
        $file = new PHPFileExtractor();
        $file->addVisitor(new ContainerAwareTrans());
        $file->addVisitor(new ContainerAwareTransChoice());
        $file->addVisitor(new FlashMessage());
        $file->addVisitor(new FormTypeChoices());
        $file->addVisitor(new FormTypeLabelExplicit());
        $file->addVisitor(new FormTypeLabelImplicit());

        return $file;
    }

    /**
     * @return TwigFileExtractor
     */
    private function getTwigFileExtractor()
    {
        $file = new TwigFileExtractor(TwigEnvironmentFactory::create());

        if (\Twig_Environment::MAJOR_VERSION === 1) {
            $file->addVisitor(new TranslationBlock());
            $file->addVisitor(new TranslationFilter());
        } else {
            $file->addVisitor(new Twig2TranslationBlock());
            $file->addVisitor(new Twig2TranslationFilter());
        }

        return $file;
    }
}
