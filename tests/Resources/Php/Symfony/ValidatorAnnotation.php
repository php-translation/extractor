<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValidatorAnnotation
{

    #[Assert\NotNull(message: "start.null")]
    private string $start;

    #[NotBlank(message: "end.blank")]
    private string $end;
}
