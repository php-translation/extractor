<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FormTypeFollowedByClass extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('field', null, [
            'label' => 'form.field.label'
        ]);
    }
}

class ClassFollowingFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('field', null, [
            'label' => 'should.not.be.extracted'
        ]);
    }
}
