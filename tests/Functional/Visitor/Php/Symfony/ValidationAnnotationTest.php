<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Symfony;

use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\ValidationAnnotation;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ValidationAnnotationTest extends BasePHPVisitorTest
{
    public function testExtractAnnotation()
    {
        $factory = new LazyLoadingMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $extractor = new ValidationAnnotation($factory);
        $collection = $this->getSourceLocations($extractor, Resources\Php\Symfony\ValidatorAnnotation::class);

        $this->assertCount(2, $collection);
        $source = $collection->get(0);
        $this->assertEquals('start.null', $source->getMessage());
        $this->assertEquals('validators', $source->getContext()['domain']);

        $source = $collection->get(1);
        $this->assertEquals('end.blank', $source->getMessage());
        $this->assertEquals('validators', $source->getContext()['domain']);
    }

    public function testExtractAnnotationError()
    {
        $factory = new LazyLoadingMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $extractor = new ValidationAnnotation($factory);
        $collection = $this->getSourceLocations($extractor, Resources\Php\Symfony\ValidatorAnnotationError::class);

        $errors = $collection->getErrors();
        $this->assertCount(1, $errors);
    }
}
