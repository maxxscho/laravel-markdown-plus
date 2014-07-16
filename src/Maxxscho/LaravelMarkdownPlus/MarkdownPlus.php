<?php namespace Maxxscho\LaravelMarkdownPlus;

use Config;
use Symfony\Component\Yaml\Yaml;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdown;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdownExtra;
use Maxxscho\LaravelMarkdownPlus\Exceptions\TooFewSectionsException;

class MarkdownPlus
{
    /**
     * @var Maxxscho\LaravelMarkdownPlus\Document
     */
    protected $document;



    /**
     * Constructor
     * Set the markdown parser and instantiate the document instance
     *
     * @param null $document
     */
    public function __construct($document = null)
    {
        $extra          = Config::get('laravel-markdown-plus::use_extra');
        $markdownParser = ($extra) ? new MichelfMarkdownExtra() : new MichelfMarkdown();

        if ($document === null)
        {
            $this->document = function () use ($markdownParser)
            {
                return new Document($markdownParser);
            };
        }
        else
        {
            $this->document = $document;
        }
    }



    /**
     * Parse the meta and content section and pass it to the
     * document class
     *
     * @param string $source
     * @return mixed
     */
    public function make($source)
    {
        if (Config::get('laravel-markdown-plus::use_meta'))
        {
            $meta    = $this->parseMeta($this->parseHeader($source));
            $content = $this->parseContent($source);
        }
        else
        {
            $content = $source;
        }

        $document = call_user_func($this->document);

        if (isset($meta))
        {
            $document->setMeta($meta);
        }

        $document->setContent($content);

        return $document;
    }



    /**
     * Parses the header section
     *
     * @param string $source
     * @return string
     */
    protected function parseHeader($source)
    {
        return $this->parseSection($source, 0);
    }



    /**
     * Parses the content section
     *
     * @param $source
     * @return string
     */
    protected function parseContent($source)
    {
        return $this->parseSection($source, 1);
    }



    /**
     * Parses the 2 sections (gets the splitter from config)
     *
     * @param $source
     * @param $offset
     * @return string
     * @throws Exceptions\TooFewSectionsException
     */
    protected function parseSection($source, $offset)
    {
        $sections = preg_split(Config::get('laravel-markdown-plus::section_splitter'), $source, 2);
        if (count($sections) != 2) throw new TooFewSectionsException();

        return trim($sections[$offset]);
    }



    /**
     * Parses the meta with symfony yaml
     *
     * @param      $source
     * @param bool $exceptionOnInvalidType
     * @param bool $objectSupport
     * @return array
     */
    protected function parseMeta($source, $exceptionOnInvalidType = false, $objectSupport = false)
    {
        return Yaml::parse($source, $exceptionOnInvalidType, $objectSupport);
    }
}