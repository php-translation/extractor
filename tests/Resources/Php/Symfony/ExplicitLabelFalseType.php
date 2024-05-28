<?php

use Symfony\Component\Form\AbstractType;
use Translation\Extractor\Attribute\Ignore;

class ExplicitLabelFalseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trans.issue_68', null, array(
                'label' => false
            ));
    }
}
