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
            ->add('field_with_function_as_attr_value', 'text', array(
                'attr' => $this->getAttrFunction()
            ))
            ->add('field_placeholder', 'choice', array(
                'placeholder' => 'form.choice_placeholder'
            ))
        ;
    }

    protected function getAttrFunction() {
      return array('maxlength' => 10);
    }
}
