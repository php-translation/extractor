<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

class AnonymousClasses extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('before', null, [
            'label' => 'before.anonymous.class'
        ]);
        $builder->get('before')->addModelTransformer(new class implements DataTransformerInterface {
            public function transform($value): mixed
            {
                return ['label' => 'inside.anonymous.class'];
            }

            public function reverseTransform($value): mixed
            {
                return $value;
            }
        });
        $builder->add('after', null, [
            'label' => 'after.anonymous.class'
        ]);
    }
}

class ClassWithAnonymousFormType
{
    public function createType(): AbstractType
    {
        $type = new class extends AbstractType {
            public function buildForm(FormBuilderInterface $builder, array $options): void
            {
                $builder->add('field', null, [
                    'label' => 'inside.anonymous.form.type'
                ]);
            }
        };

        $notAForm = ['label' => 'after.anonymous.form.type'];

        return $type;
    }
}
