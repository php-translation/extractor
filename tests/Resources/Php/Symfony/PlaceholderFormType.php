<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlaceholderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_with_attr_placeholder', 'text', array(
                'label' => 'field.with.placeholder',
                'attr' => array('placeholder' => 'form.placeholder.text')
            ))
            ->add('field_without_label_with_attr_placeholder', 'text', array(
                'label' => false,
                'attr' => array('placeholder' => 'form.placeholder.text.but.no.label')
            ))
            ->add('choice_field_placeholder', 'choice', array(
                'placeholder' => 'form.choice_placeholder'
            ))
            ->add('date_field_placeholder', 'date', array(
                'placeholder' => [
                    'year' => 'form.date_placeholder.year',
                    'month' => 'form.date_placeholder.month',
                    'day' => 'form.date_placeholder.day'
                ],
            ))
        ;
    }
}
