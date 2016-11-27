<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Twig;

use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;
use Translation\Extractor\Visitor\Twig\TranslationBlock;
use Translation\Extractor\Visitor\Twig\TranslationFilter;

class TranslationBlockTest extends BaseTwigVisitorTest
{
    public function testTrans()
    {
        $collection = $this->getSourceLocations(new TranslationBlock(), 'Twig/TranslationBlock/trans.html.twig');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
        $this->assertEquals('domain', $source->getContext()['domain']);
    }

    public function testTranschoice()
    {
        $collection = $this->getSourceLocations(new TranslationBlock(), 'Twig/TranslationBlock/transchoice.html.twig');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
    }
}
