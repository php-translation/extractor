<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ParentType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('parent_field', null, [
            'label' => 'parent.field.label'
        ]);
    }
}
