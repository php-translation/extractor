<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\Visitor\Php\SourceLocationContainerExtractor;
use Translation\Extractor\Tests\Resources;

class SourceLocationContainerExtractorTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new SourceLocationContainerExtractor(), Resources\Php\SourceLocationContainer::class);

        $this->assertCount(2, $collection);
        $source = $collection->first();
        $this->assertEquals('foo', $source->getMessage());
    }
}
