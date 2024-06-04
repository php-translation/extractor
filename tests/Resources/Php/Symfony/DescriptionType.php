<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Translation\Extractor\Annotation\Desc;

class DescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foobar', TextType::class, array(
                /** @Desc("Foobar:") */
                'label' => 'test_label',
            ));
    }
}
