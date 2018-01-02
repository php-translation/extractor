<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class FormDomainChoiceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('test_a', null, [
            'choices' => [
                'label1_a' => 'key1',
                'label2_a' => 'key2',
            ],
            'choice_translation_domain' => 'admin',
        ]);

        // Make sure this will have domain "messages"
        $builder->add('test_b', null, [
            'choices' => [
                'label1_b' => 'key1',
                'label2_b' => 'key2',
            ],
        ]);

        $builder->add('test_C', null, [
            'choices' => [
                'label1_c' => 'key1',
                'label2_c' => 'key2',
            ],
            'choice_translation_domain' => false,
        ]);
    }
}