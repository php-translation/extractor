<?php

namespace Translation\Extractor\Tests\Resources\Php\Symfony;

class ImplicitLabelType
{
    const ISSUE_131 = 'issue_131';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $var = 'test';
        $builder->add('find1'); // find1
        $builder->add('bigger_find2');
        $builder->add('camelFind3');
        $builder->add('skip1'.$var);
        $builder->add('skip2', null, ['label'=>'valid']);
        $builder->add(function () { return 'skip3'; });
        // Symfony will throw an error I guess, but at least extractions skip it
        $builder->add('');

		//issue87: support for Ignore annotation
		$generateOptions = function() { return ['label' => false]; };
        $builder->add('issue87-willBeAdded', null, $generateOptions);
        $builder->add(/** @Ignore */'issue87-shouldNotBeAdded', null, $generateOptions);

        //issue131: support for constant as option key
        $builder->add('issue_131', null, [self::ISSUE_131 => true]);
    }
}
