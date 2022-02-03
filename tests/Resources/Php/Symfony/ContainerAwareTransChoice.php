<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ContainerAwareTransChoice
{
    /**
     * @return array
     */
    public function newAction()
    {
        $translated = $this->get('translator')->transChoice('foobar');

        return array();
    }

    public function transWithVariable()
    {
        $var = 'toto';
        $translated = $this->get('translator')->transChoice($var);
    }
}
