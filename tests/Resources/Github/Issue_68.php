<?php

use Symfony\Component\Form\AbstractType;
use Translation\Extractor\Annotation\Ignore;

class LableIsFalse extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trans.issue_68', null, array(
                'label' => false
            ));
    }
}