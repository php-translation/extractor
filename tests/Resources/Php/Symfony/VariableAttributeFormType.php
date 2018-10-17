<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VariableAttributeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ignoredVariable = 'ignored_attr';
        $builder
            ->add('field_with_untranslatable_attr_key', 'choice', array(
                'attr' => array(
                    $ignoredVariable => 'form.ignored_translation',
                )
            ))
        ;
    }
}
