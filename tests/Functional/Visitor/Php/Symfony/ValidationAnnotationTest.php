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

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;
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
        //use correct factory class depending on whether using Symfony 2 or 3
        if (class_exists('Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory')) {
            $metadataFactoryClass = 'Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory';
        } else {
            $metadataFactoryClass = 'Symfony\Component\Validator\Mapping\ClassMetadataFactory';
        }

        $factory = new $metadataFactoryClass(new AnnotationLoader(new AnnotationReader()));
        $extractor = new ValidationAnnotation($factory);
        $collection = $this->getSourceLocations($extractor, Resources\Php\Symfony\ValidatorAnnotation::class);

        $this->assertCount(2, $collection);
        $source = $collection->get(0);
        $this->assertEquals('start.null', $source->getMessage());
        $source = $collection->get(1);
        $this->assertEquals('end.blank', $source->getMessage());
    }
}
