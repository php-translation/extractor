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

use Translation\Extractor\Visitor\Twig\TranslationBlock;

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
