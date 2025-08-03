<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

$config = new PhpCsFixer\Config();

return $config
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        'phpdoc_order' => true,
        'method_chaining_indentation' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
        'array_syntax' => ['syntax' => 'short'],
        'yoda_style' => false,
        'phpdoc_align' => ['align' => 'left'],
        'no_empty_phpdoc' => true,
        'protected_to_private' => true,
        'cast_spaces' => ['space' => 'none'],
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_no_empty_return' => false,
        'phpdoc_separation' => false,
        'phpdoc_summary' => false,
        'no_unneeded_control_parentheses' => false,
        'compact_nullable_type_declaration' => true,
        'fully_qualified_strict_types' => true,
        'linebreak_after_opening_tag' => true,
        'multiline_comment_opening_closing' => true,
        'no_alternative_syntax' => true,
        'no_binary_string' => true,
        'no_null_property_initialization' => true,
        'no_useless_return' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_var_annotation_correct_order' => true,
        'strict_comparison' => true,
        'declare_strict_types' => true,
        'blank_line_before_statement' => true,
        'psr_autoloading' => ['dir' => './src'],
    ])
    ->setFinder($finder)
    ;
;
