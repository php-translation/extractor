<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\extractor\tests\Functional\Visitor\Php\Symfony;

use Translation\Extractor\Tests\Functional\Visitor\Php\BasePHPVisitorTest;
use Translation\Extractor\Tests\Resources;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeEmptyValue;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FormEmptyValueTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeEmptyValue(), Resources\Php\Symfony\EmptyValueType::class);

        $this->assertCount(3, $collection);

        $this->assertEquals('gender.empty_value', $collection->get(0)->getMessage());
        $this->assertEquals('birthday.form.year', $collection->get(1)->getMessage());
        $this->assertEquals('birthday.form.month ', $collection->get(2)->getMessage());
    }
}
