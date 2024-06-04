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
            ->add('field_with_help', 'text', array(
                'label' => 'field.with.placeholder',
                'help' => $string
            ))
            ->add('field_without_label_with_help', 'text', array(
                'label' => false,
                'help' => $string
            ))
        ;
    }
}
