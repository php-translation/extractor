<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class Constraint
{
    /**
     * @return array
     */
    public function newAction()
    {
        $notBlankConstraint = new NotBlank([
            'message' => 'error.custom_not_blank',
        ]);

        $lengthConstraint = new Assert\Length([
            'min' => 6,
            'minMessage' => 'error.custom_length',
        ]);

        return [];
    }
}
