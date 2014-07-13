<?php namespace Maxxscho\LaravelMarkdownPlus;

use Maxxscho\LaravelMarkdownPlus\Parser\MarkdownParserInterface;
use Config;

class Document
{
    protected $content;

    protected $meta = [];

    protected $markdownParser;



    public function __construct(MarkdownParserInterface $markdownParser)
    {
        $this->markdownParser = $markdownParser;
        $this->setParserOptions();
    }



    public function setContent($content)
    {
        $this->content = $content;
    }



    public function getContent()
    {
        return $this->markdownParser->render($this->content);
    }



    public function getRawContent()
    {
        return $this->content;
    }



    public function setMeta($meta)
    {
        $this->meta = $meta;
    }



    public function getMeta()
    {
        return $this->meta;
    }



    public function __call($name, $args)
    {
        if (array_key_exists($name, $this->meta))
        {
            return $this->meta[$name];
        }

        return $this->meta;
    }



    protected function setParserOptions()
    {
        $options = Config::get('laravel-markdown-plus::markdown_parser_options');

        foreach ($options as $key => $value)
        {
            if (property_exists($this->markdownParser, $key))
            {
                $this->markdownParser->$key = $value;
            }
        }
    }
}