<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Attribute;

#[\Attribute]
class Translate
{
    private string $domain = '';

    /**
     * Translate constructor.
     */
    public function __construct(array $values = ['domain' => 'messages'])
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
