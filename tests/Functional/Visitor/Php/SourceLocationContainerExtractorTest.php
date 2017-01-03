<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\Tests\Resources\Php\Symfony\SourceLocationContainer;
use Translation\Extractor\Visitor\Php\SourceLocationContainerExtractor;
use Translation\Extractor\Tests\Resources;

class SourceLocationContainerExtractorTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new SourceLocationContainerExtractor(), Resources\Php\SourceLocationContainer::class);

        $this->assertCount(2, $collection);
        $source = $collection->first();
        $this->assertEquals('foo', $source->getMessage());
    }
}
