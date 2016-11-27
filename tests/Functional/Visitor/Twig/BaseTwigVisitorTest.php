<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Twig;

use Translation\Extractor\FileExtractor\TwigFileExtractor;
use Translation\Extractor\Tests\Functional\Visitor\BaseVisitorTest;

abstract class BaseTwigVisitorTest extends BaseVisitorTest
{
    /**
     * @return TwigFileExtractor
     */
    protected function getExtractor()
    {
        return new TwigFileExtractor();
    }
}
