<?php

use Symfony\Component\Form\AbstractType;
use Translation\Extractor\Attribute\Ignore;

class EmptyValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foo', null, array(
                /** @Ignore */
                'label' => 'trans.issue_62'
            ));

    }
}
