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

/**
 * @Annotation
 */
class Translate
{
    /**
     * @var string
     */
    private $domain = 'messages';

    /**
     * Translate constructor.
     */
    public function __construct(array $values)
    {
        if (isset($values['domain'])) {
            $this->domain = $values['domain'];
        }
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
