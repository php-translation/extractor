<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ExplicitLabelType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $var = "something";
        $builder->add('find1', null, [
            'label' => 'label.find1'
        ]);
        $builder
            ->add('find2', null, array(
                'label' => 'find2'
            ))
            ->add('field_longer_name', null, [
                'label' => 'FOUND3 '
            ])
            ->add('skip1', null, [
                'label' => $var,
                'somethingelse' => 'skipthis',
            ])
            ->add('skip2', null, [
                'label' => PHP_OS, // constant shouldn't work
                'somethingelse' => 'skipthis',
            ])
            ->add('skip3', null, [
                'label' // value label, shouldn't be picked up
            ])
            ->add('skip4', null, [
                'label' => 'something '.$var
            ])
        ;
        $builder->add('skip5', null);

        $arr = ['label'=>'find4.label'];
        $builder->add('find3', null, $arr);
    }
}