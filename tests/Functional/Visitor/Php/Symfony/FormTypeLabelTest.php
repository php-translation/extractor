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
use Translation\Extractor\Visitor\Php\Symfony\FormTypeChoices;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeEmptyValue;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeInvalidMessage;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelExplicit;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeLabelImplicit;
use Translation\Extractor\Visitor\Php\Symfony\FormTypePlaceholder;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeTitle;

/**
 * @author Rein Baarsma <rein@solidwebcode.com>
 */
class FormTypeLabelTest extends BasePHPVisitorTest
{
    private $allFormVisitors;

    public function __construct()
    {
        $this->allFormVisitors = [
            new FormTypeChoices(),
            new FormTypeEmptyValue(),
            new FormTypeInvalidMessage(),
            new FormTypeLabelExplicit(),
            new FormTypeLabelImplicit(),
            new FormTypePlaceholder(),
            new FormTypeTitle(),
        ];

        parent::__construct();
    }

    public function testTranslationDomain()
    {
        $collection = $this->getSourceLocations($this->allFormVisitors, Resources\Php\Symfony\FormDomainType::class);

        // We should not have "test_d" or "test_e"
        $this->assertEquals(3, $collection->count(), 'We should ignore choices where "translation_domain" is "false"');

        $messageA = $collection->get(2);
        $this->assertEquals('label1', $messageA->getMessage());
        $this->assertEquals('admin0', $messageA->getContext()['domain']);

        $messageB = $collection->get(0);
        $this->assertEquals('Test b', $messageB->getMessage());
        $this->assertEquals('admin1', $messageB->getContext()['domain']);

        $messageC = $collection->get(1);
        $this->assertEquals('Test c', $messageC->getMessage());
        $this->assertNull($messageC->getContext()['domain']);
    }
}
