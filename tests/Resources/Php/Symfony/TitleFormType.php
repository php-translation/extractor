<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TitleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_with_attr_title', 'text', array(
                'label' => 'field.with.title',
                'attr' => array('title' => 'form.title.text')
            ))
            ->add('field_without_label_with_attr_title', 'text', array(
                'label' => false,
                'attr' => array('title' => 'form.title.text.but.no.label')
            ))
        ;
    }
}
