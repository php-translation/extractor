<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class HelpFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_with_help', 'text', array(
                'label' => 'field.with.help',
                'help' => 'form.help.text'
            ))
            ->add('field_without_label_with_help', 'text', array(
                'label' => false,
                'help' => 'form.help.text.but.no.label'
            ))
        ;
    }
}
