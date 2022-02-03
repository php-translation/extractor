<?php

namespace Translation\Extractor\Tests\Resources\Github;

class NotAnArrayAttrType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('field_with_function_as_attr_value', 'text', array(
            'attr' => $this->getAttrFunction()
          ));
    }

    public function getAttrFunction()
    {
        return array('maxlength' => 10);
    }
}
