<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src');

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@Symfony:risky' => true,
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PSR1' => true,
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_var_without_name' => false,
        'list_syntax' => ['syntax' => 'short'],
        'heredoc_to_nowdoc' => true,
        'linebreak_after_opening_tag' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'multiline_whitespace_before_semicolons' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'echo_tag_syntax' => true,
        'phpdoc_order' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last'],

        //ARRAY
        'array_push' => true,
        'modernize_strpos' => true,
        'no_whitespace_before_comma_in_array' => true,
        'trim_array_spaces' => true,

        //Casing
        'constant_case' => ['case' => 'lower'],
        'lowercase_keywords' => true,

        //CAST
        'magic_constant_casing' => true,
        'lowercase_cast' => true,

        //Control Structure
        'control_structure_continuation_position' => ['position' => 'same_line'],
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    ])
    ->setFinder($finder);
