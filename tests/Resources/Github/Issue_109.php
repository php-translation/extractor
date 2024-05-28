<?php

namespace Translation\Extractor\Tests\Resources\Github;
use Translation\Extractor\Attribute\Ignore;

class MustNotBeIgnoredType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('field_a', 'text', array(
        'label' => 'github.issue_109.a',
        /** @Ignore */ // cannot replace these with attributes, it will syntax error
        'placeholder' => 'github.issue_109.b',
      ))
      ->add('field_b', 'text', array(
        /** @Ignore */// cannot replace these with attributes, it will syntax error
        'label' => 'github.issue_109.c',
        'placeholder' => 'github.issue_109.d',
      ));
  }
}
