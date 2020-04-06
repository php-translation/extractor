<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional\Visitor\Php\Knp;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Knp\Menu\ItemLabel;
use Translation\Extractor\Visitor\Php\Knp\Menu\LinkTitle;

final class MenuTest extends BasePHPVisitorTest
{
    public function testExtractOne()
    {
        $collection = $this->getSourceLocations(new ItemLabel(),
            Resources\Php\Knp\Menu::class);

        $this->assertCount(4, $collection);
        $this->assertEquals('my.first.label', $collection->get(0)->getMessage());
        $this->assertEquals('foo', $collection->get(1)->getMessage());
        $this->assertEquals('foo.first.label', $collection->get(2)->getMessage());
        $this->assertEquals('foo.second.label', $collection->get(3)->getMessage());
    }

    public function testExtractTwo()
    {
        $collection = $this->getSourceLocations(new LinkTitle(),
            Resources\Php\Knp\Menu::class);

        $this->assertCount(2, $collection);
        $this->assertEquals('my.first.title', $collection->get(0)->getMessage());
        $this->assertEquals('my.second.title', $collection->get(1)->getMessage());
    }
}
