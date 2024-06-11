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
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelImplicit;

/**
 * @author Rein Baarsma <rein@solidwebcode.com>
 */
class FormTypeLabelImplicitTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeLabelImplicit(), ImplicitLabelType::class);

        $this->assertCount(5, $collection, print_r($collection, true));
        $this->assertEquals('Find1', $collection->get(0)->getMessage());
        $this->assertEquals('Bigger find2', $collection->get(1)->getMessage());
        $this->assertEquals('Camel find3', $collection->get(2)->getMessage());

        // issue87: support for Ignore annotation
        $this->assertEquals('Issue87-will be added', $collection->get(3)->getMessage());
    }
}
