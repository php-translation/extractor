<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

function getChoices() {
    return [
        'label1' => 'key'
    ];
};

class SimpleChoicePassFunctionType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('issue_76', null, [
            'choices' => getChoices(),
        ]);
    }
}
