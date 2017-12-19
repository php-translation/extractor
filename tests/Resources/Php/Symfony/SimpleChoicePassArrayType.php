<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class SimpleChoicePassArrayType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $validOptions = [
            'label1' => 'key'
        ];

        $builder->add('test', null, [
            'choices' => $validOptions,
        ]);
    }
}