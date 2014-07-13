<?php

return [
    'section_splitter' => '/\s+-{3,}\s+/',
    /*
    |--------------------------------------------------------------------------
    | Use Markdown Extra
    |--------------------------------------------------------------------------
    |
    | Use Markdown with extra, for Example
    | https://michelf.ca/projects/php-markdown/extra/#fenced-code-blocks
    */

    'use_extra'            => false,

    'markdown_parser_options' => [
        /*
        |--------------------------------------------------------------------------
        | Suffix for empty HTML Elements
        |--------------------------------------------------------------------------
        |
        | This is the string used to close tags for HTML elements with no content
        | such as <br> and <hr>. The default value creates XML-style empty
        | element tags which are also valid in HTML 5
        */
        'empty_element_suffix' => ' />',
        'tab_width'            => 4,
        'no_markup'            => false,
        'no_entities'          => false,
        'predef_urls'          => [],
        'predef_titles'        => [],

        'fn_id_prefix'         => '',
        'fn_link_title'        => '',
        'fn_backlink_title'    => '',
        'fn_link_class'        => 'footnote-ref',
        'fn_backlink_class'    => 'footnote-backref',
        'code_class_prefix'    => '',
        'code_attr_on_pre'     => false,
        'predef_abbr'          => [],
    ],
];