<?php

namespace Translation\Extractor\Tests\Resources\Github;

class Issue104Type
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $model = new \stdClass();
        $setter = 'setMethod';
        $model->$setter(null);
    }
}
