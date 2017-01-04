<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ChainedChoiceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('test', null, [
            'choices' => [
                'label1' => 'key',
                'label2' => 'key',
            ],
        ]);
    }
}
