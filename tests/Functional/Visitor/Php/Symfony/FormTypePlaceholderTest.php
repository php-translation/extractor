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
use Translation\Extractor\Visitor\Php\Symfony\FormTypePlaceholder;

class FormTypePlaceholderTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypePlaceholder(), Resources\Php\Symfony\PlaceholderFormType::class);

        $this->assertCount(3, $collection);
        $this->assertEquals('form.placeholder.text', $collection->get(0)->getMessage());
        $this->assertEquals('form.placeholder.text.but.no.label', $collection->get(1)->getMessage());
        $this->assertEquals('form.choice_placeholder', $collection->get(2)->getMessage());
    }

}
