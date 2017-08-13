<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValidatorAnnotationError
{
    /**
     * @foobar This should be an Error
     */
    private $foo;
}
