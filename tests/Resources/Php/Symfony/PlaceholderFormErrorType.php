<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Translation\Extractor\Annotation\Ignore;

class PlaceholderFormErrorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $string = 'foo';
        $builder
            ->add('field_with_attr_placeholder', 'text', array(
                'label' => 'field.with.placeholder',
                'attr' => array('placeholder' => $string)
            ))
            ->add('field_without_label_with_attr_placeholder', 'text', array(
                'label' => false,
                'attr' => array('placeholder' => $string)
            ))
            ->add('field_placeholder', 'choice', array(
                'placeholder' => $string
            ))
            ->add('field_placeholder_ignore2', 'choice', array(
                /** @Ignore */
                'placeholder' => $string
            ))
        ;
    }
}
