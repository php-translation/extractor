<?php


namespace Translation\Extractor\Tests\Resources\Php;

use Translation\Extractor\Annotation\Translate;
use Translation\Extractor\Annotation\Desc;

class TestTranslateAnnotationFile
{
    const string SOME_CONST = /** @Translate */'const_for_translation';

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
