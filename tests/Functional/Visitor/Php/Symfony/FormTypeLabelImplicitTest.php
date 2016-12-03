<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelImplicit;

class FormTypeLabelImplicitTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeLabelImplicit(), Resources\Php\Symfony\ImplicitLabelType::class);

        $this->assertCount(3, $collection, print_r($collection, true));
        $this->assertEquals('find1', $collection->get(0)->getMessage());
        $this->assertEquals('bigger_find2', $collection->get(1)->getMessage());
        $this->assertEquals('camelFind3', $collection->get(2)->getMessage());
    }
}