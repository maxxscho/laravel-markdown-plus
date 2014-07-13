<?php namespace Maxxscho\LaravelMarkdownPlus\Parser;

use Michelf\MarkdownExtra;

class MichelfMarkdownExtra extends MarkdownExtra implements MarkdownParserInterface
{
    /**
     * Render the content markdown --> html
     *
     * @param $content
     * @return mixed
     */
    public function render($content)
    {
        return $this->transform($content);
    }
}