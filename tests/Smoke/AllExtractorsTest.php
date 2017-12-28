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

use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
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
use Translation\Extractor\Visitor\Twig\TwigVisitorFactory;

/**
 * Smoke test to make sure no extractor throws exceptions.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class AllExtractorsTest extends TestCase
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
        $this->translationMissing($sc, 'trans.issue_62');
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
     * Assert that a translation key is missing in source collection.
     *
     * @param SourceCollection $sc
     * @param $translationKey
     * @param string $message
     */
    private function translationMissing(SourceCollection $sc, $translationKey, $message = null)
    {
        if (empty($message)) {
            $message = sprintf('The translation key "%s" should not exist', $translationKey);
        }

        $found = false;
        foreach ($sc as $source) {
            if ($translationKey === $source->getMessage()) {
                $found = true;

                break;
            }
        }

        $this->assertFalse($found, $message);
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
        $file->addVisitor(TwigVisitorFactory::create());

        return $file;
    }
}
