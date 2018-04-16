<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class Issue104Type
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $model = new \stdClass();
        $setter = 'setMethod';
        $model->$setter(null);
    }
}
