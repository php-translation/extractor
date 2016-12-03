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
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeChoices;

class FormTypeChoicesTest extends BasePHPVisitorTest
{
    public function testSimpleSymfony3x()
    {
        $collection = $this->getSourceLocations(new FormTypeChoices(), Resources\Php\Symfony\SimpleChoiceSymfony3xType::class);

        $this->assertCount(1, $collection, print_r($collection, true));
        $this->assertEquals('label1', $collection->get(0)->getMessage());
        $this->assertEquals(10, $collection->get(0)->getLine());
    }

    public function testSimpleSymfony27()
    {
        $visitor = new FormTypeChoices();
        $visitor->setSymfonyMajorVersion(2);
        $collection = $this->getSourceLocations($visitor, Resources\Php\Symfony\SimpleChoiceSymfony27Type::class);

        $this->assertCount(4, $collection, print_r($collection, true));
        $this->assertEquals('label1', $collection->get(0)->getMessage());
        $this->assertEquals('label2', $collection->get(1)->getMessage());
        $this->assertEquals('label3', $collection->get(2)->getMessage());
        $this->assertEquals('label4', $collection->get(3)->getMessage());
        $this->assertEquals(12, $collection->get(0)->getLine());
    }
}
