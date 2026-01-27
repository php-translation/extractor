<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\FormBuilderInterface;

class IsFormType extends ParentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('child_field', null, [
            'label' => 'child.field.label'
        ]);
    }
}
