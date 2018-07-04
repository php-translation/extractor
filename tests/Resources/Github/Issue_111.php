<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Translation\Extractor\Annotation\Ignore;

class Issue111Type
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      /** @Ignore */
      'choices' => function (Options $options) {
        return array(
          'github.issue_110.a' => 'a',
          'github.issue_110.b' => 'b'
        );
      },
      'foo_bar' => true
    ));
  }
}
