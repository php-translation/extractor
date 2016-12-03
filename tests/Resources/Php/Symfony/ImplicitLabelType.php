<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ImplicitLabelType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $var = 'test';
        $builder->add('find1'); // find1
        $builder->add('bigger_find2');
        $builder->add('camelFind3');
        $builder->add('skip1'.$var);
        $builder->add('skip2', null, ['label'=>'valid']);
        $builder->add(function () { return 'skip3'; });
        // Symfony will throw an error I guess, but at least extractions skip it
        $builder->add('');
    }
}