<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Twig;

use Translation\Extractor\Visitor\Php\Symfony\FlashMessages;
use Translation\Extractor\Visitor\Twig\TranslationFilter;

class TranslationFilterTest extends BaseTwigVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new TranslationFilter(), 'Twig/TranslationFilter.html.twig');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
    }
}
