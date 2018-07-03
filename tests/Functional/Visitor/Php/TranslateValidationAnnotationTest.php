<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Symfony;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Tests\Resources\Php\Symfony\ValidatorAnnotationVariableFail;
use Translation\Extractor\Tests\Resources\Php\Symfony\ValidatorAnnotationVariablePass;
use Translation\Extractor\Visitor\Php\Symfony\ValidationAnnotation;
use Translation\Extractor\Visitor\Php\TranslateAnnotationVisitor;

class TranslateValidationAnnotationTest extends BasePHPVisitorTest
{
    protected function getValidatorAnnotationVisitor()
    {
        $factory = new LazyLoadingMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        return new ValidationAnnotation($factory);
    }

    public function testAnnotationFailFile()
    {
        $translateVisitorCall = $this->getSourceLocations(new TranslateAnnotationVisitor(),
            ValidatorAnnotationVariableFail::class);
        $this->assertCount(1, $translateVisitorCall);

        $annotationVisitorCall = $this->getSourceLocations($this->getValidatorAnnotationVisitor(),
            ValidatorAnnotationVariableFail::class);
        // there will be error here so test will fail!
        $this->assertCount(0, $annotationVisitorCall->getErrors());
    }

    public function testAnnotationPassFile()
    {
        $translateVisitorCall = $this->getSourceLocations(new TranslateAnnotationVisitor(),
            ValidatorAnnotationVariablePass::class);
        $this->assertCount(1, $translateVisitorCall);

        $annotationVisitorCall = $this->getSourceLocations($this->getValidatorAnnotationVisitor(),
            ValidatorAnnotationVariablePass::class);
        $this->assertCount(0, $annotationVisitorCall->getErrors());
    }
}
