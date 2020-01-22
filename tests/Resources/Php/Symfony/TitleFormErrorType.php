<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TitleFormErrorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $string = 'foo';
        $builder
            ->add('field_with_attr_title', 'text', array(
                'label' => 'field.with.title',
                'attr' => array('title' => $string)
            ))
            ->add('field_without_label_with_attr_title', 'text', array(
                'label' => false,
                'attr' => array('title' => $string)
            ))
        ;
    }
}
