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
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelImplicit;

/**
 * @author Rein Baarsma <rein@solidwebcode.com>
 */
class FormTypeLabelImplicitTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeLabelImplicit(), Resources\Php\Symfony\ImplicitLabelType::class);

        $this->assertCount(5, $collection, print_r($collection, true));
        $this->assertEquals('find1', $collection->get(0)->getMessage());
        $this->assertEquals('bigger_find2', $collection->get(1)->getMessage());
        $this->assertEquals('camelFind3', $collection->get(2)->getMessage());

        //issue87: support for Ignore annotation
        $this->assertEquals('issue87-willBeAdded', $collection->get(3)->getMessage());
    }
}
