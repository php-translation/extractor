<?php

namespace Translation\Extractor\Tests\Resources\Github;

use Translation\Extractor\Annotation\Ignore;

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
