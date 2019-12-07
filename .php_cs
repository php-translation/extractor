<?php

$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => array('syntax' => 'short'),
        'native_function_invocation' => true,
        'ordered_imports' => true,
        'declare_strict_types' => false,
        'single_import_per_statement' => false,

        // Should be true
        'native_function_invocation' => false,
        'no_superfluous_phpdoc_tags' => false,
        'phpdoc_types_order' => false,
        'standardize_increment' => false,
        'phpdoc_to_comment' => false,
        'single_blank_line_at_eof' => false,
        'ordered_imports' => false,
        'no_extra_blank_lines' => false,
        'psr4' => false,
        'trailing_comma_in_multiline_array' => false,
        'braces' => false,
        'array_syntax' => false,
        'single_blank_line_before_namespace' => false,
        'blank_line_after_namespace' => false,
        'binary_operator_spaces' => false,
        'no_whitespace_in_blank_line' => false,
        'function_declaration' => false,
        'indentation_type' => false,
        'class_attributes_separation' => false,
        'no_unused_imports' => false,
        'single_quote' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
            ->exclude(__DIR__.'/tests/Resources')
            ->name('*.php')
    )
;

return $config;
