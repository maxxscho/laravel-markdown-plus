<?php namespace Maxxscho\LaravelMarkdownPlus\Parser;

use Michelf\Markdown;

class MichelfMarkdown extends Markdown implements MarkdownParserInterface
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