<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FlashMessages;

class FlashMessagesTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FlashMessages(), Resources\Php\Symfony\FlashMessages::class);

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('flash.created', $source->getMessage());
    }
}
