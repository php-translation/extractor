<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ContainerAwareTrans
{
    /**
     * @return array
     */
    public function newAction()
    {
        $translated = $this->get('translator')->trans('foobar');

        return array();
    }
}
