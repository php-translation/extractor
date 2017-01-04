<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ContainerAwareTrans
{
    /**
     * @return array
     */
    public function newAction()
    {
        // Using container
        $translated = $this->get('translator')->trans('trans0');

        // As a parameter
        $model->setMessage(
            $this->get('translator')->trans(
                'trans1',
                array(
                    'a' => 'x',
                    'b' => 'y',
                    'c' => 'z',
                )
            )
        );

        return array();
    }

    public function getLine()
    {
        return $this->translator->trans('trans_line');
    }
    public function getTranslatorVariable()
    {
        $translator = $this->get('translator');
        $translator->trans('variable');
    }
}
