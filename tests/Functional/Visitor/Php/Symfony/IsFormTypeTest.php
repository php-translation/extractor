<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;

class IsFormTypeTest extends BasePHPVisitorTest
{
    public function testParentFormTypeExtractsTranslations(): void
    {
        $collection = $this->getSourceLocations(
            new FormTypeLabelExplicit(),
            Resources\Php\Symfony\ParentType::class
        );

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('parent.field.label', $source->getMessage());
    }

    public function testInheritedFormTypeIsProperlyDetected(): void
    {
        $collection = $this->getSourceLocations(
            new FormTypeLabelExplicit(),
            Resources\Php\Symfony\IsFormType::class
        );

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('child.field.label', $source->getMessage());
    }

    public function testNonFormTypeDoesNotExtractTranslations(): void
    {
        $collection = $this->getSourceLocations(
            new FormTypeLabelExplicit(),
            Resources\Php\Symfony\NotAFormType::class
        );

        $this->assertCount(0, $collection);
    }
}
