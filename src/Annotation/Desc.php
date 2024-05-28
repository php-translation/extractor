<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Annotation;

use JetBrains\PhpStorm\Deprecated;

/**
 * @Annotation
 */
#[Deprecated]
final class Desc
{
    /**
     * @var string
     */
    public $text;
}
