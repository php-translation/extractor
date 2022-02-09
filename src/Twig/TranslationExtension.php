<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TranslationExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('desc', [$this, 'runDescFilter']),
        ];
    }

    public function runDescFilter($v)
    {
        return $v;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'php-translation';
    }
}
