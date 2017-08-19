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

use Translation\Extractor\Visitor\Twig\TwigVisitorFactory;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TranslationFilterTest extends BaseTwigVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(TwigVisitorFactory::create(), 'Twig/TranslationFilter/trans.html.twig');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
    }
}
