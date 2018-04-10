<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class GlobalTranslationDomainWithPlaceholderType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'attr' => array(
                    'placeholder' => 'github.issue_96a.placeholder',
                ),
                'label' => 'github.issue_96a.label'
            ])
            ->add('password', null, [
                'attr' => array(
                    'placeholder' => 'github.issue_96b.placeholder',
                ),
                'label' => 'github.issue_96b.label',
                'translation_domain' => 'foobar'
            ])
            ->add('age', null, [
                'placeholder' => 'github.issue_96c.placeholder',
                'label' => 'github.issue_96c.label',
                'translation_domain' => 'foobar'
            ])
            ->add('location', null, [
                'placeholder' => 'github.issue_96d.placeholder',
                'label' => 'github.issue_96d.label',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'custom',
        ]);
    }
}
