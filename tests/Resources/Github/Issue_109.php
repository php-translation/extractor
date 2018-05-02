<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;
use Translation\Extractor\Annotation\Ignore;

class MustNotBeIgnoredType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('field_a', 'text', array(
        'label' => 'github.issue_109.a',
        /** @Ignore */
        'placeholder' => 'github.issue_109.b',
      ))
      ->add('field_a', 'text', array(
        /** @Ignore */
        'label' => 'github.issue_109.c',
        'placeholder' => 'github.issue_109.d',
      ));
  }
}