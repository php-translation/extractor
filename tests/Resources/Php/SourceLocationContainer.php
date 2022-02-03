<?php

namespace Translation\Extractor\Tests\Resources\Php;

use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\TranslationSourceLocationContainer;

class SourceLocationContainer implements TranslationSourceLocationContainer
{
    public static function getTranslationSourceLocations(): array
    {
        return [
            SourceLocation::createHere('foo'),
            SourceLocation::createHere('bar'),
        ];
    }
}
