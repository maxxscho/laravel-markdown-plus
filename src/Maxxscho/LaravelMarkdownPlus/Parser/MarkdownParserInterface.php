<?php namespace Maxxscho\LaravelMarkdownPlus\Parser;

interface MarkdownParserInterface
{

    /**
     * Render the content markdown --> html
     *
     * @param $content
     * @return mixed
     */
    public function render($content);
}