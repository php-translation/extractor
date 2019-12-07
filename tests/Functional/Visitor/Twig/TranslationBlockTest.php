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

use Symfony\Bridge\Twig\TokenParser\TransChoiceTokenParser;
use Translation\Extractor\Visitor\Twig\TwigVisitorFactory;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TranslationBlockTest extends BaseTwigVisitorTest
{
    public function testTrans()
    {
        $collection = $this->getSourceLocations(TwigVisitorFactory::create(), 'Twig/TranslationBlock/trans.html.twig');

        $this->assertCount(3, $collection);
        $source = $collection->get(0);
        $this->assertEquals('foobar', $source->getMessage());
        $this->assertEquals('domain', $source->getContext()['domain']);

        $source = $collection->get(1);
        $this->assertEquals('no-domain', $source->getMessage());
        $this->assertEquals('messages', $source->getContext()['domain']);

        $source = $collection->get(2);
        $this->assertEquals('trans-count', $source->getMessage());
        $this->assertEquals('messages', $source->getContext()['domain']);
    }

    public function testTranschoice()
    {
        if (!class_exists(TransChoiceTokenParser::class)) {
            $this->markTestSkipped('Transchoice is not available anymore.');
        }

        $collection = $this->getSourceLocations(TwigVisitorFactory::create(), 'Twig/TranslationBlock/transchoice.html.twig');

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
    }
}
