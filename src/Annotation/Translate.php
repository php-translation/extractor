<?php

namespace Translation\Extractor\Annotation;

/**
 * @Annotation
 */
class Translate
{
    /** @var string  */
    private $domain = 'messages';

    /**
     * Translate constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['domain'])) {
            $this->domain = $values['domain'];
        }
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

}