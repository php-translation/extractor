<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Translation\Extractor\Model\SourceLocation;

class SourceLocationTest extends TestCase
{
    public function testCreateHere()
    {
        $location = SourceLocation::createHere('foobar', ['foo' => 'bar']);

        $this->assertContains('tests/Unit/Model/SourceLocationTest.php', $location->getPath());
        $this->assertEquals(21, $location->getLine());

        $this->assertEquals('foobar', $location->getMessage());
        $this->assertEquals(['foo' => 'bar'], $location->getContext());
    }
}
