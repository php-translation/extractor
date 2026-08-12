<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeChoices;

/**
 * @author Rein Baarsma <rein@solidwebcode.com>
 */
class FormTypeChoicesTest extends BasePHPVisitorTest
{
    public function testSimpleSymfony3x(): void
    {
        $collection = $this->getSourceLocations(new FormTypeChoices(), SimpleChoiceSymfony3xType::class);

        $this->assertCount(1, $collection, print_r($collection, true));
        $this->assertEquals('label1', $collection->get(0)->getMessage());
        $this->assertEquals(10, $collection->get(0)->getLine());
    }

    public function testSimpleSymfony27(): void
    {
        $visitor = new FormTypeChoices();
        $visitor->setSymfonyMajorVersion(2);
        $collection = $this->getSourceLocations($visitor, SimpleChoiceSymfony27Type::class);

        $this->assertCount(4, $collection, print_r($collection, true));
        $this->assertEquals('label1', $collection->get(0)->getMessage());
        $this->assertEquals('label2', $collection->get(1)->getMessage());
        $this->assertEquals('label3', $collection->get(2)->getMessage());
        $this->assertEquals('label4', $collection->get(3)->getMessage());
        $this->assertEquals(12, $collection->get(0)->getLine());
    }

    public function testChainedChoice(): void
    {
        $visitor = new FormTypeChoices();
        $visitor->setSymfonyMajorVersion(3);
        $collection = $this->getSourceLocations($visitor, ChainedChoiceType::class);

        $this->assertCount(2, $collection, print_r($collection, true));
        $this->assertEquals('label1', $collection->get(0)->getMessage());
        $this->assertEquals('label2', $collection->get(1)->getMessage());
    }

    public function testChainedChoiceExtension(): void
    {
        $visitor = new FormTypeChoices();
        $visitor->setSymfonyMajorVersion(3);
        $collection = $this->getSourceLocations($visitor, ChainedChoicedTypeExtension::class);

        $this->assertCount(2, $collection, print_r($collection, true));
        $this->assertEquals('label1', $collection->get(0)->getMessage());
        $this->assertEquals('label2', $collection->get(1)->getMessage());
    }

    public function testExtractError(): void
    {
        $collection = $this->getSourceLocations(new FormTypeChoices(), SimpleChoiceSymfony3xErrorType::class);

        $errors = $collection->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testPassedChoices(): void
    {
        $collection = $this->getSourceLocations(new FormTypeChoices(), SimpleChoicePassArrayType::class);

        $this->assertCount(1, $collection, print_r($collection, true));
        $this->assertEquals('label1', $collection->get(0)->getMessage());
        $this->assertEquals(9, $collection->get(0)->getLine());
    }

    public function testChoiceTranslationDomain(): void
    {
        $collection = $this->getSourceLocations(new FormTypeChoices(), FormDomainChoiceType::class);

        $messageA = $collection->get(0);
        $this->assertEquals('label1_a', $messageA->getMessage());
        $this->assertEquals('admin', $messageA->getContext()['domain']);

        $messageB = $collection->get(2);
        $this->assertEquals('label1_b', $messageB->getMessage());
        $this->assertNull($messageB->getContext()['domain']);

        // We should not have "test_c"
        $this->assertEquals(4, $collection->count(), 'We should ignore choices where "choice_translation_domain" is "false"');
    }
}
