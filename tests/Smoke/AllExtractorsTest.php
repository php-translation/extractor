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

use Symfony\Bridge\Twig\Translation\TwigExtractor;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\MessageCatalogue;
use Translation\Extractor\Extractor;
use Translation\Extractor\FileExtractor\PHPFileExtractor;
use Translation\Extractor\FileExtractor\TwigFileExtractor;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Tests\Functional\Visitor\Twig\TwigEnvironmentFactory;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTrans;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTransChoice;
use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeChoices;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelImplicit;
use Translation\Extractor\Visitor\Twig\TranslationBlock;
use Translation\Extractor\Visitor\Twig\TranslationFilter;
use Translation\Extractor\Visitor\Twig\Twig1Visitor;
use Translation\Extractor\Visitor\Twig\Twig2TranslationBlock;
use Translation\Extractor\Visitor\Twig\Twig2TranslationFilter;
use Translation\Extractor\Visitor\Twig\Twig2Visitor;

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

        $sc = $extractor->extract($finder);
        $this->translationExists($sc, 'trans.issue_34');
    }

    /**
     * Assert that a translation key exists in source collection.
     *
     * @param SourceCollection $sc
     * @param $translationKey
     * @param string $message
     */
    private function translationExists(SourceCollection $sc, $translationKey, $message = null)
    {
        if (empty($message)) {
            $message = sprintf('Tried to find "%s" but failed', $translationKey);
        }

        $found = false;
        foreach ($sc as $source) {
            if ($translationKey === $source->getMessage()) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, $message);
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
            $file->addVisitor(new Twig1Visitor());
        } else {
            $file->addVisitor(new Twig2Visitor());
        }

        return $file;
    }
}
