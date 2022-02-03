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
use Translation\Extractor\Visitor\Php\Symfony\FormTypeHelp;

final class FormTypeHelpTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeHelp(),
            Resources\Php\Symfony\HelpFormType::class);

        $this->assertCount(2, $collection);
        $this->assertEquals('form.help.text', $collection->get(0)->getMessage());
        $this->assertEquals('form.help.text.but.no.label', $collection->get(1)->getMessage());
    }

    public function testExtractError()
    {
        $collection = $this->getSourceLocations(new FormTypeHelp(),
            Resources\Php\Symfony\HelpFormErrorType::class);

        $errors = $collection->getErrors();
        $this->assertCount(2, $errors);
    }

    public function testChildVisitationNotBlocked()
    {
        $collection = $this->getSourceLocations(
            [
                new FormTypeHelp(),
                new ContainerAwareTrans(),
            ],
            Resources\Php\Symfony\ContainerAwareTrans::class
        );

        $this->assertCount(6, $collection);

        $this->assertEquals('trans0', $collection->get(0)->getMessage());
        $this->assertEquals('trans1', $collection->get(1)->getMessage());
        $this->assertEquals('trans_line', $collection->get(2)->getMessage());
        $this->assertEquals('variable', $collection->get(3)->getMessage());
        $this->assertEquals('my.pdf', $collection->get(4)->getMessage());
        $this->assertEquals('bar', $collection->get(5)->getMessage());
    }
}
