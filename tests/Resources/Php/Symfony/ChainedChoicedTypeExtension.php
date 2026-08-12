<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ChainedChoicedTypeExtension implements FormTypeExtensionInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('test', null, [
                'choices' => [
                    'label1' => 'key',
                    'label2' => 'key',
                ],
            ]);
    }
}
