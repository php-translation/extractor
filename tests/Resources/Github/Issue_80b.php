<?php

use Symfony\Component\Form\AbstractType;

class PlaceholderAsBooleanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foo', null, array(
                'placeholder' => false,
            ));
    }
}
