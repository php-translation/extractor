<?php

declare(strict_types=1);

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\Tests\Resources\Php\Symfony\DescriptionType;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;

final class DescriptionTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeLabelExplicit(), DescriptionType::class);

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('test_label', $source->getMessage());
        $this->assertEquals('Foobar:', $source->getContext()['desc']);
    }
}
