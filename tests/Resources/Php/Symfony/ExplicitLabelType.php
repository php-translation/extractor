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
            ->add('field_longer_name3', null, [
                'label' => 'FOUND3 '
            ])
            ->add('skip1', null, [
                'label' => $var,
                'somethingelse' => 'skipthis',
            ])
            ->add('skip2', null, [
                'label' => PHP_OS, // constant shouldn't work
            ])
            ->add('skip3', null, [
                'label' // value label, shouldn't be picked up
            ])
            ->add('skip4', null, [
                'label' => 'something '.$var
            ])
        ;

        // implicit label should not be found
        $builder->add('skip5', null);

        // add label in variable should be found
        $arr = ['label'=>'find4.label'];
        $builder->add('find4', null, $arr);

        // empty label should be skipped
        $builder->add('skip6', null, ['label'=>'']);

        // collection test
        $builder->add('find5', 'collection', array(
            'options' => array(
                'label' => 'label.find5'
            )
        ));
    }
}