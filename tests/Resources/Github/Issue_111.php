<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Translation\Extractor\Annotation\Ignore;

class Issue111Type
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('field_1', null, array(
            /** @Ignore */
            'choices' => array(
                'github.issue_111.c' => 'c'
            )
        ));

        $builder->add('field_2', null, array(
            'choices' => array(
                'github.issue_111.d' => 'd'
            )
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            /** @Ignore */
            'choices' => function (Options $options) {
                return array(
                    'github.issue_111.a' => 'a',
                    'github.issue_111.b' => 'b'
                );
            },
            'foo_bar' => true
        ));
    }
}
