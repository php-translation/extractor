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

use Translation\Extractor\Visitor\Twig\TwigVisitor;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TranslationFilterTest extends BaseTwigVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new TwigVisitor(), 'Twig/TranslationFilter/trans.html.twig');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
    }

    public function testDescExtract()
    {
        $collection = $this->getSourceLocations(new TwigVisitor(), 'Twig/TranslationFilter/desc.html.twig');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
        $this->assertEquals('baz', $source->getContext()['desc']);
    }

    public function testDescExtractError()
    {
        $collection = $this->getSourceLocations(new TwigVisitor(), 'Twig/TranslationFilter/desc-error.html.twig');

        $errors = $collection->getErrors();
        $this->assertCount(2, $errors);
    }
}
