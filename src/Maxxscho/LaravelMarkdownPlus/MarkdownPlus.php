<?php namespace Maxxscho\LaravelMarkdownPlus;

use Maxxscho\LaravelMarkdownPlus\Exceptions\TooFewSectionsException;
use Config;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdown;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdownExtra;
use Symfony\Component\Yaml\Yaml;

class MarkdownPlus
{
    public function __construct()
    {

    }



    public function make($source)
    {
        $meta    = $this->parseMeta($this->parseHeader($source));
        $content = $this->parseContent($source);

        $extra = Config::get('laravel-markdown-plus::use_extra');

        $markdownParser = ($extra) ? new MichelfMarkdownExtra() : new MichelfMarkdown();

        $document = new Document($markdownParser);
        $document->setMeta($meta);
        $document->setContent($content);

        return $document;
    }



    protected function parseHeader($source)
    {
        return $this->parseSection($source, 0);
    }



    protected function parseContent($source)
    {
        return $this->parseSection($source, 1);
    }



    protected function parseSection($source, $offset)
    {
        $sections = preg_split(Config::get('laravel-markdown-plus::section_splitter'), $source, 2);
        if (count($sections) != 2) throw new TooFewSectionsException();

        return trim($sections[$offset]);
    }



    protected function parseMeta($source, $exceptionOnInvalidType = false, $objectSupport = false)
    {
        return Yaml::parse($source, $exceptionOnInvalidType, $objectSupport);
    }


    /*
     * $file = File::get('file');
     * $document = MarkdownPlus::make($file);
     *
     * $html = $document->getContent();
     * $raw = $document->getRawContent();
     * $allMeta = $document->meta();
     * $title = $document->title(); --> dynamische funktion
     */
}