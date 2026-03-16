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

trigger_deprecation('php-translation/extractor', '2.3', 'The "%s" class is deprecated and will be removed soon. It is now considered as a PHPDoc tag.', Ignore::class);
/**
 * @deprecated since 2.3, this class is not used anymore. @Ignore is now considered as a PHPDoc tag.
 *
 * @Annotation
 */
final class Ignore
{
}
