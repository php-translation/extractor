<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\FormBuilderInterface;

class NotAFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('field', null, [
            'label' => 'should.not.be.extracted'
        ]);
    }
}
