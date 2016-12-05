<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
