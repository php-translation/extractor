<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class FormDomainType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('test_a', null, [
            'label' => 'label1',
            'translation_domain' => 'admin0',
        ]);

        $builder->add('test_b', null, [
            'translation_domain' => 'admin1',
        ]);

        // Make sure this will have domain "messages"
        $builder->add('test_c', null, [
        ]);

        $builder->add('test_d', null, [
            'translation_domain' => false,
        ]);

        $builder->add('test_e', null, [
            'label' => 'label2',
            'translation_domain' => false,
        ]);
    }
}