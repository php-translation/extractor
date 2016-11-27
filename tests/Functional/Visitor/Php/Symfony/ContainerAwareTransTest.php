<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTrans;


class ContainerAwareTransTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new ContainerAwareTrans(), Resources\Php\Symfony\ContainerAwareTrans::class);

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('foobar', $source->getMessage());
    }
}
