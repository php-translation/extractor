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

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Translation\Extractor\Twig\Translation3Extension;
use Translation\Extractor\Twig\TranslationExtension as PHPTranslationExtension;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Create a TwigEnvironment that will be used in tests.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TwigEnvironmentFactory
{
    public static function create()
    {
        if(-1 === version_compare(Environment::VERSION, '3.0')) {

            $env = new \Twig_Environment(new \Twig_Loader_Array([]));
            $env->addExtension(new TranslationExtension($translator = new IdentityTranslator(new MessageSelector())));
            $env->addExtension(new PHPTranslationExtension());

            return $env;
        }

        $env = new Environment(new ArrayLoader());
        $env->addExtension(new TranslationExtension($translator = new IdentityTranslator()));
        $env->addExtension(new Translation3Extension());

        return $env;
    }
}
