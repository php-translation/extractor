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

    /**
     * @see https://github.com/php-translation/extractor/issues/197
     */
    public function testFormTypeDetectionDoesNotLeakToNextFile(): void
    {
        $visitor = new FormTypeLabelExplicit();

        $collection = $this->getSourceLocations($visitor, Resources\Php\Symfony\ParentType::class);
        $this->assertCount(1, $collection);

        $collection = $this->getSourceLocations($visitor, Resources\Php\Symfony\NotAFormType::class);
        $this->assertCount(0, $collection);
    }

    /**
     * @see https://github.com/php-translation/extractor/issues/197
     */
    public function testFormTypeDetectionDoesNotLeakToNextClass(): void
    {
        $collection = $this->getSourceLocations(
            new FormTypeLabelExplicit(),
            Resources\Php\Symfony\FormTypeFollowedByClass::class
        );

        $this->assertCount(1, $collection);
        $this->assertEquals('form.field.label', $collection->first()->getMessage());
    }

    /**
     * The form type detection is restored once an anonymous class ends, in both directions.
     */
    public function testAnonymousClasses(): void
    {
        $collection = $this->getSourceLocations(
            new FormTypeLabelExplicit(),
            Resources\Php\Symfony\AnonymousClasses::class
        );

        $messages = [];
        foreach ($collection as $source) {
            $messages[] = $source->getMessage();
        }

        $this->assertEquals([
            'before.anonymous.class',
            'after.anonymous.class',
            'inside.anonymous.form.type',
        ], $messages);
    }
}
