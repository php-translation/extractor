<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class SimpleChoiceSymfony27Type
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $var = true;

        $builder->add('find1', null, [
            'choices' => [
                'key' => 'label1'
            ]
        ]);

        $builder->add('find2', null, [
            'choices' => [
                'label2' => 'key'
            ],
            'choices_as_values' => true
        ]);

        $builder->add('find3', null, [
            'choices' => [
                $var => 'label3'
            ]
        ]);

        $builder->add('find4', null, [
            'choices' => [
                'label4'
            ]
        ]);

        $builder->add('skip1', null, [
            'choices' => [
                'key' => $var
            ]
        ]);
    }
}