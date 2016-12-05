<?php

namespace Translation\Extractor\Tests\Functional\Visitor\Twig;

use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class TwigEnvironmentFactory
{
    public static function create()
    {
        $env = new \Twig_Environment();
        $env->addExtension(new TranslationExtension($translator = new IdentityTranslator(new MessageSelector())));
        $env->setLoader(new \Twig_Loader_String());

        return $env;
    }
}
