<?php

declare(strict_types=1);

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\Tests\Resources\Php\Symfony\DescriptionType;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;

final class DescriptionTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeLabelExplicit(), DescriptionType::class);

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('test_label', $source->getMessage());
        $this->assertEquals('Foobar:', $source->getContext()['desc']);
    }
}
