<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Common\Annotations\AnnotationRegistry;

require __DIR__.'/../vendor/autoload.php';

if (!class_exists(AnnotationRegistry::class, false) && class_exists(AnnotationRegistry::class)) {
    if (method_exists(AnnotationRegistry::class, 'registerUniqueLoader')) {
        AnnotationRegistry::registerUniqueLoader('class_exists');
    } elseif (method_exists(AnnotationRegistry::class, 'registerLoader')) {
        AnnotationRegistry::registerLoader('class_exists');
    }
}
