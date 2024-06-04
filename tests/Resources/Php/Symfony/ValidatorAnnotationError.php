<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;


class ValidatorAnnotationError
{
    #[Foobar('this should be an error')]
    private string $foo;
}
