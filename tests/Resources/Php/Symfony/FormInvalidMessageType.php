<?php

namespace Translation\extractor\tests\Resources\Php\Symfony;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FormInvalidMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, array(
            'type' => 'password',
            'first_name' => 'new',
            'second_name' => 'confirm',
            'first_options' => array('label' => 'label.password.new'),
            'second_options' => array('label' => 'label.password.confirm'),
            'invalid_message' => 'password.not_match', // <--- This message
            'options' => array(),
            'required' => true,
        ));
    }
}
