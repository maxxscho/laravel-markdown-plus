<?php

use Maxxscho\LaravelMarkdownPlus\MarkdownPlus;
use Mockery as M;

class MarkdownPlusTest extends PHPUnit_Framework_TestCase
{
    protected $config;



    public function setUp()
    {
        parent::setUp();

        $this->config = M::mock('Config');
    }



    public function tearDown()
    {
        M::close();
    }



    public function testMake()
    {
        $this->configUseMeta();
        $this->configSectionSplitter(2);
        $this->config->shouldReceive('get')
            ->with('laravel-markdown-plus::markdown_parser_options')
            ->once()
            ->andReturn([
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
            ]);

        $mp = $this->markdownPlusInstance();

        $source = file_get_contents(__DIR__ . '/fixtures/document_01.md');

        $document = $mp->make($source);

        $this->assertInstanceOf('Maxxscho\LaravelMarkdownPlus\Document', $document);
        $this->assertEquals('Testdocument 01', $document->title());
    }



    public function testParseSectionCanParseOffset()
    {
        $this->configSectionSplitter(2);
        $mp = $this->markdownPlusInstance();
        $f = "first\n-------\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals('second', $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseCanHandleExtraNewLines()
    {
        $this->configSectionSplitter(2);
        $mp = $this->markdownPlusInstance();
        $f = "first\n\n\n-------\n\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals('second', $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseSectionCanHandleMultipleNewLines()
    {
        $this->configSectionSplitter(2);
        $mp = $this->markdownPlusInstance();
        $f = "first\n\n\n-----\n--\n\n\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals("--\n\n\nsecond", $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseSectionBreaksWithoutNewLines()
    {
        $this->setExpectedException('Maxxscho\LaravelMarkdownPlus\Exceptions\TooFewSectionsException');
        $this->configSectionSplitter();
        $mp = $this->markdownPlusInstance();
        $f = "first----second";
        $this->invokeMethod($mp, 'parseSection', [$f, 0]);
    }



    public function testParseSectionLessThanThreeDashes()
    {
        $this->setExpectedException('Maxxscho\LaravelMarkdownPlus\Exceptions\TooFewSectionsException');
        $this->configSectionSplitter();
        $mp = $this->markdownPlusInstance();
        $f = "first\n--\nsecond";
        $this->invokeMethod($mp, 'parseSection', [$f, 0]);
    }



    public function testParseSecionnWithManyDashes()
    {
        $this->configSectionSplitter(2);
        $mp = $this->markdownPlusInstance();
        $f = "first\n------------------------\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals('second', $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseHeader()
    {
        $this->configSectionSplitter();
        $mp = $this->markdownPlusInstance();
        $f = file_get_contents(__DIR__ . '/fixtures/document_02.md');

        $this->assertEquals('title: Testdocument02', $this->invokeMethod($mp, 'parseHeader', [$f]));
    }



    public function testParseContent()
    {
        $this->configSectionSplitter();
        $mp = $this->markdownPlusInstance();
        $f = file_get_contents(__DIR__ . '/fixtures/document_02.md');

        $this->assertEquals('This is the content', $this->invokeMethod($mp, 'parseContent', [$f]));
    }



    public function testParseMeta()
    {
        $this->configSectionSplitter();
        $mp = $this->markdownPlusInstance();
        $f      = file_get_contents(__DIR__ . '/fixtures/document_01.md');
        $header = $this->invokeMethod($mp, 'parseHeader', [$f]);
        $meta   = $this->invokeMethod($mp, 'parseMeta', [$header]);

        $this->assertCount(3, $meta);
        $this->assertEquals('Testdocument 01', $meta['title']);
    }



    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }



    protected function markdownPlusInstance()
    {
        $this->config
            ->shouldReceive('get')
            ->with('laravel-markdown-plus::use_extra')
            ->andReturn(true);

        return new MarkdownPlus($this->config);
    }



    protected function configUseMeta($return = true)
    {
        $this->config
            ->shouldReceive('get')
            ->once()
            ->with('laravel-markdown-plus::use_meta')
            ->andReturn($return);
    }



    protected function configSectionSplitter($times = 1, $return = '/\s+-{3,}\s+/')
    {
        $this->config
            ->shouldReceive('get')
            ->times($times)
            ->with('laravel-markdown-plus::section_splitter')
            ->andReturn($return);
    }
}