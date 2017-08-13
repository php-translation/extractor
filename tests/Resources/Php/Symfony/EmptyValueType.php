<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmptyValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, array(
                'choices_as_values' => true,
                'empty_value' => 'gender.empty_value', // <-- here
                'choices' => array(
                    'gender.female' => 0,
                    'gender.male' => 1,
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'empty_value' => array(
                'year' => 'birthday.form.year',
                'month' => 'birthday.form.month',
            ),
            'error_bubbling' => false,
        ));
    }


}
