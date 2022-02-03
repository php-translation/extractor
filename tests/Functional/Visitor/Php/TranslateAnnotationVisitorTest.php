<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\Tests\Resources\Php\TestTranslateAnnotationFile;
use Translation\Extractor\Visitor\Php\TranslateAnnotationVisitor;

final class TranslateAnnotationVisitorTest extends BasePHPVisitorTest
{
    public function testExtract()
    {
        $collection = $this->getSourceLocations(new TranslateAnnotationVisitor(),
            TestTranslateAnnotationFile::class);

        $this->assertCount(4, $collection);

        $this->assertEquals('const_for_translation', $collection->get(0)->getMessage());
        $this->assertEquals(['domain' => 'messages'], $collection->get(0)->getContext());

        $this->assertEquals('x_to_messages_implicit', $collection->get(1)->getMessage());
        $this->assertEquals(['domain' => 'messages'], $collection->get(1)->getContext());

        $this->assertEquals('y_to_messages_explicit', $collection->get(2)->getMessage());
        $this->assertEquals(['domain' => 'messages'], $collection->get(2)->getContext());

        $this->assertEquals('z_to_validators_explicit', $collection->get(3)->getMessage());
        $this->assertEquals(['domain' => 'validators'], $collection->get(3)->getContext());
    }
}
