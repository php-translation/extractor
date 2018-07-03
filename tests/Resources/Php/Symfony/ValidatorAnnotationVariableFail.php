<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ValidatorAnnotationVariableFail
{
    protected $someVar = /** @Translate */'some_var';
}
