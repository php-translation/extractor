<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EmptyValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('github.issue_78a', HiddenType::class, array(
            ))
            ->add('github.issue_78b', 'Symfony\Component\Form\Extension\Core\Type\HiddenType', array(
            ));

    }
}
