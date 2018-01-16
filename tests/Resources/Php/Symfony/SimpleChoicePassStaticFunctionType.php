<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;


class SimpleChoicePassStaticFunctionType
{

    public static function getChoices() {
        return [
            'labe1' => 'key'
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('issue_76', null, [
            'choices' => Resources\Php\Symfony\SimpleChoicePassStaticFunctionType::getChoices(),
        ]);
    }
}
