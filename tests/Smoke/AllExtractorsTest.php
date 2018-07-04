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
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Tests\Functional\Visitor\Twig\TwigEnvironmentFactory;
use Translation\Extractor\Visitor\Php\SourceLocationContainerVisitor;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTrans;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTransChoice;
use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeChoices;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeEmptyValue;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeInvalidMessage;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelImplicit;
use Translation\Extractor\Visitor\Php\Symfony\FormTypePlaceholder;
use Translation\Extractor\Visitor\Php\TranslateAnnotationVisitor;
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
        $this->translationMissing($sc, 'github.issue_78a');
        $this->translationMissing($sc, 'github.issue_78b');

        $source = $this->translationExists($sc, 'github.issue_82a');
        $this->assertEquals('custom', $source->getContext()['domain']);
        $source = $this->translationExists($sc, 'github.issue_82b');
        $this->assertEquals('foobar', $source->getContext()['domain']);
        $source = $this->translationExists($sc, 'github.issue_82c');
        $this->assertEquals('custom', $source->getContext()['domain']);

        $source = $this->translationExists($sc, 'github.issue_96a.placeholder');
        $this->assertEquals('custom', $source->getContext()['domain']);
        $source = $this->translationExists($sc, 'github.issue_96b.placeholder');
        $this->assertEquals('foobar', $source->getContext()['domain']);
        $source = $this->translationExists($sc, 'github.issue_96c.placeholder');
        $this->assertEquals('foobar', $source->getContext()['domain']);
        $source = $this->translationExists($sc, 'github.issue_96d.placeholder');
        $this->assertEquals('custom', $source->getContext()['domain']);

        $this->translationExists($sc, 'github.issue_109.a');
        $this->translationMissing($sc, 'github.issue_109.b');

        $this->translationMissing($sc, 'github.issue_109.c');
        $this->translationExists($sc, 'github.issue_109.d');

        /*
         * It is okey to increase the error count if you adding more fixtures/code.
         * We just need to be aware that it changes.
         */
        $this->assertCount(12, $sc->getErrors(), 'There was an unexpected number of errors. Please investigate.');
    }

    /**
     * Assert that a translation key exists in source collection.
     *
     * @param SourceCollection $sc
     * @param $translationKey
     * @param string $message
     *
     * @return SourceLocation
     */
    private function translationExists(SourceCollection $sc, $translationKey, $message = null)
    {
        if (empty($message)) {
            $message = sprintf('Tried to find "%s" but failed', $translationKey);
        }

        $source = null;
        $found = false;
        foreach ($sc as $source) {
            if ($translationKey === $source->getMessage()) {
                $found = true;

                break;
            }
        }

        $this->assertTrue($found, $message);

        return $source;
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
        $file->addVisitor(new FormTypeEmptyValue());
        $file->addVisitor(new FormTypeInvalidMessage());
        $file->addVisitor(new FormTypeLabelExplicit());
        $file->addVisitor(new FormTypeLabelImplicit());
        $file->addVisitor(new FormTypePlaceholder());
        $file->addVisitor(new SourceLocationContainerVisitor());
        $file->addVisitor(new TranslateAnnotationVisitor());

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
