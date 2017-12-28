<?php

namespace Translation\Extractor\Twig;

class TranslationExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('desc', array($this, 'desc')),
        );
    }

    public function desc($v)
    {
        return $v;
    }

    public function getName()
    {
        return 'php-translation';
    }
}