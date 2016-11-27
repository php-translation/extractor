<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Php;

use Translation\Extractor\FileExtractor\PHPFileExtractor;
use Translation\Extractor\Tests\Functional\Visitor\BaseVisitorTest;

abstract class BasePHPVisitorTest extends BaseVisitorTest
{
    /**
     * @return PHPFileExtractor
     */
    protected function getExtractor()
    {
        return new PHPFileExtractor();
    }
}
