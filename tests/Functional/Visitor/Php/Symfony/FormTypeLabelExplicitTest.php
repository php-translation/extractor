<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;

class FormTypeLabelExplicitTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeLabelExplicit(), Resources\Php\Symfony\ExplicitLabelType::class);

        $this->assertCount(5, $collection);
        $this->assertEquals(10, $collection->get(0)->getLine());
        $this->assertEquals('label.find1', $collection->get(0)->getMessage());
        $this->assertEquals('find2', $collection->get(1)->getMessage());
        $this->assertEquals('FOUND3 ', $collection->get(2)->getMessage());
        $this->assertEquals('find4.label', $collection->get(3)->getMessage());
        $this->assertEquals('label.find5', $collection->get(4)->getMessage());
    }

    public function testWillNotExtractTypeless()
    {
        $collection = $this->getSourceLocations(new FormTypeLabelExplicit(), Resources\Php\Symfony\ExplicitLabelTypeless::class);
        $this->assertCount(0, $collection);
    }
}
