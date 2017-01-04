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
use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class FlashMessageTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new FlashMessage(), Resources\Php\Symfony\FlashMessage::class);

        $this->assertCount(1, $collection);
        $source = $collection->first();
        $this->assertEquals('flash.created', $source->getMessage());
    }
}
