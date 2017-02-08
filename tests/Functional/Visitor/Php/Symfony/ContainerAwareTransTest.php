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
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTrans;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ContainerAwareTransTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new ContainerAwareTrans(), Resources\Php\Symfony\ContainerAwareTrans::class);

        $this->assertCount(6, $collection);

        $this->assertEquals('trans0', $collection->get(0)->getMessage());
        $this->assertEquals('trans1', $collection->get(1)->getMessage());
        $this->assertEquals('trans_line', $collection->get(2)->getMessage());
        $this->assertEquals('variable', $collection->get(3)->getMessage());
        $this->assertEquals('my.pdf', $collection->get(4)->getMessage());
        $this->assertEquals('bar', $collection->get(5)->getMessage());
    }
}
