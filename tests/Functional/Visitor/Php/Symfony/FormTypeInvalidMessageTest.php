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
use Translation\Extractor\Visitor\Php\Symfony\FormTypeInvalidMessage;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FormTypeInvalidMessageTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FormTypeInvalidMessage(), Resources\Php\Symfony\FormInvalidMessage::class);

        $found = false;
        foreach ($collection as $source) {
            if ('password.not_match' === $source->getMessage()) {
                $this->assertEquals('validators', $source->getContext()['domain']);
                $found = true;
            }
        }

        $this->assertTrue($found, 'We must be able to extract the form\'s "invalid_message".');
    }
}
