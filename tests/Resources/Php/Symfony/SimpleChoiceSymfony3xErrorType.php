<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Translation\Extractor\Annotation\Ignore;

class SimpleChoiceSymfony3xErrorType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $string = 'foo';

        $builder->add('test', null, [
            'choices' => [
                /** @Ignore */
                $string => 'key',
            ],
        ]);

        $builder->add('test', null, [
            'choices' => [
                $string => 'key',
            ],
        ]);
    }
}
