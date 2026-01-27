<?php

use Symfony\Component\Form\AbstractType;

class PlaceholderAsBooleanType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foo', null, array(
                'placeholder' => false,
            ));
    }
}
