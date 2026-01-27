<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class SimpleChoiceSymfony3xType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('test', null, [
            'choices' => [
                'label1' => 'key'
            ],
        ]);
    }
}