<?php namespace Maxxscho\LaravelMarkdownPlus;

use Config;
use Maxxscho\LaravelMarkdownPlus\Parser\MarkdownParserInterface;

class Document
{
    /**
     * @var
     */
    protected $content;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var Parser\MarkdownParserInterface
     */
    protected $markdownParser;



    /**
     * Constructor
     *
     * @param MarkdownParserInterface $markdownParser
     */
    public function __construct(MarkdownParserInterface $markdownParser)
    {
        $this->markdownParser = $markdownParser;
        $this->setParserOptions();
    }



    /**
     * Sets the content
     *
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }



    /**
     * Get the parsed content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->markdownParser->render($this->content);
    }



    /**
     * Get the raw content
     *
     * @return mixed
     */
    public function getRawContent()
    {
        return $this->content;
    }



    /**
     * Set the meta array
     *
     * @param array $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }



    /**
     * Get the whole meta array
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }



    /**
     * Dynamic method to get a meta directly
     *
     * @param $name
     * @param $args
     * @return array
     */
    public function __call($name, $args)
    {
        if (array_key_exists($name, $this->meta))
        {
            return $this->meta[$name];
        }

        return $this->meta;
    }



    /**
     * Sets the markdown parser options
     */
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