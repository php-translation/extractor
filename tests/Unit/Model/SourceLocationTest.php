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

use Translation\Extractor\Model\SourceLocation;

class SourceLocationTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateHere()
    {
        $location = SourceLocation::createHere('foobar', ['foo'=>'bar']);

        $this->assertEquals('/Users/tobias/Workspace/PHPStorm/Translation/extractor/tests/Unit/Model/SourceLocationTest.php', $location->getPath());
        $this->assertEquals(20, $location->getLine());

        $this->assertEquals('foobar', $location->getMessage());
        $this->assertEquals(['foo'=>'bar'], $location->getContext());
    }
}
