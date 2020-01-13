<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

use Symfony\Component\Translation\TranslatorInterface;

class ContainerAwareTrans
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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

    public function getPdfFilename(string $locale)
    {
        return $this->translator->trans('my.pdf', [], 'generic', $locale);
    }

    public function static()
    {
        $translator = static::$container->get('translator'); $foo = $translator->trans('bar');
    }

    public function transWithVariable()
    {
        $key = 'trans_key';

        // This should not be source Locations
        return $this->translator->trans($key);
    }
}
