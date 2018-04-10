<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class GlobalTranslationDomainType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'label' => 'github.issue_82a'
            ])
            ->add('password', null, [
                'label' => 'github.issue_82b',
                'translation_domain' => 'foobar'
            ])
            ->add('submit', null, [
                'label' => 'github.issue_82c',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'custom',
        ]);
    }
}
