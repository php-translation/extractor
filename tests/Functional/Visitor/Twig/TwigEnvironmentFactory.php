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

use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\IdentityTranslator;
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
        $env = new Environment(new ArrayLoader([]));
        $env->addExtension(new TranslationExtension($translator = new IdentityTranslator()));
        $env->addExtension(new PHPTranslationExtension());

        return $env;
    }
}
