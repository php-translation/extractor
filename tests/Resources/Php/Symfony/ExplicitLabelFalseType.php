<?php

use Symfony\Component\Form\AbstractType;
use Translation\Extractor\Annotation\Ignore;

class ExplicitLabelFalseType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trans.issue_68', null, array(
                'label' => false
            ));
    }
}
