<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor;

use Translation\Extractor\Model\SourceLocation;

/**
 * This interface is recognized by the extractors. Use this on your Form classes
 * or anywhere where you have dynamic translation strings.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface TranslationSourceLocationContainer
{
    /**
     * Return an array of source locations.
     *
     * @return SourceLocation[]
     */
    public static function getTranslationSourceLocations(): array;
}
