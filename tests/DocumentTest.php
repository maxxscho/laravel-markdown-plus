<?php

use Maxxscho\LaravelMarkdownPlus\Document;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdown;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdownExtra;
use Mockery as M;

class DocumentTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }



    public function tearDown()
    {
        M::close();
    }



    public function testDocumentCanBeCreated()
    {
        $d = $this->documentInstance();

        $this->assertInstanceOf('Maxxscho\LaravelMarkdownPlus\Document', $d);
    }



    public function testDocumentContentCanBeSet()
    {
        $d = $this->documentInstance();
        $d->setContent('Foo');

        $this->assertEquals('Foo', $d->getRawContent());
    }



    public function testDocumentHtmlContentCanBeReturned()
    {
        $d = $this->documentInstance();
        $d->setContent('Foo **Bar** Baz');
        $e = "<p>Foo <strong>Bar</strong> Baz</p>\n";

        $this->assertEquals($e, $d->getContent());
    }



    public function testDocumentRawContentCanBeReturned()
    {
        $d = $this->documentInstance();
        $d->setContent('Foo **Bar** Baz');
        $e = 'Foo **Bar** Baz';

        $this->assertEquals($e, $d->getRawContent());
    }



    public function testMetaCanBeSetAndReturned()
    {
        $d = $this->documentInstance();
        $d->setMeta(['title' => 'FooBar']);

        $this->assertArrayHasKey('title', $d->getMeta());
    }



    public function testSingleMetaCanBeReturnedWithDynamicMethod()
    {
        $d = $this->documentInstance();
        $d->setMeta([
            'title'    => 'FooBarTitle',
            'subtitle' => 'FooBarSubtitle',
            'tags'     => ['laravel', 'package'],
        ]);

        $this->assertEquals('FooBarTitle', $d->title());
        $this->assertEquals('FooBarSubtitle', $d->subtitle());
        $this->assertEquals('laravel', $d->tags()[0]);
    }



    public function testDocumentCanParseExtra()
    {
        $d = $this->documentInstance(true);
        $d->setContent("~~~\nCode Block\n~~~");
        $expected = "<pre><code>Code Block\n</code></pre>\n";
        $this->assertEquals($expected, $d->getContent());
    }



    private function documentInstance($extra = false)
    {
        $config = M::mock('ConfigMock');

        $config->shouldReceive('get')
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

        return ($extra) ? new Document(new MichelfMarkdownExtra, $config) : new Document(new MichelfMarkdown, $config);
    }
}