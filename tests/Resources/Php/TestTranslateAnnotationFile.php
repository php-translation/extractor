<?php


namespace Translation\Extractor\Tests\Resources\Php;

use Translation\Extractor\Attribute\Translate;
use Translation\Extractor\Attribute\Desc;

class TestTranslateAnnotationFile
{
    #[Translate]
    const SOME_CONST = 'const_for_translation';

    protected function test(): void
    {
        $a = 'not_commented';
        $b = /* some weird comment */'commented';
        $c = 'commented_too'; //some other weird comment
        $d = /* @Translate */'incorrectly_annotated';
        $e = /** @foo */'unknown_annotation';
        $f = /** @Desc(text="something) */'desc_annotation';

        $x = /** @Translate */'x_to_messages_implicit';
        $y = /** @Translate(domain="messages") */'y_to_messages_explicit';
        $z = /** @Translate(domain="validators") */'z_to_validators_explicit';
    }
}
