<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ExplicitLabelTypeless
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('field_name');
    }
}