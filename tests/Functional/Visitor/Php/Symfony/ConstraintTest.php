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
use Translation\Extractor\Visitor\Php\Symfony\Constraint;

/**
 * @author Tobias Nyholm <lucapassini@gmail.com>
 */
final class ConstraintTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new Constraint(), Resources\Php\Symfony\Constraint::class);

        $this->assertCount(2, $collection);

        $first = $collection->get(0);
        $this->assertEquals('error.custom_not_blank', $first->getMessage());

        $second = $collection->get(1);
        $this->assertEquals('error.custom_length', $second->getMessage());
    }
}
