<?php

namespace Translation\Extractor\Tests\Resources\Github;

use Translation\Extractor\Attribute\Ignore;

class ConcatenatedStrings
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator->trans(
            'github.'.
            'issue_125.'.
            'a'
        );
    }
}
