<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;

class FlashMessageTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FlashMessage(), Resources\Php\Symfony\FlashMessage::class);

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('flash.created', $source->getMessage());
    }
}
