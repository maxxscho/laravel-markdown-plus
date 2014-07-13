<?php

use Maxxscho\LaravelMarkdownPlus\Document;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdown;
use Maxxscho\LaravelMarkdownPlus\Parser\MichelfMarkdownExtra;

class DocumentTest extends \Illuminate\Foundation\Testing\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }



    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__ . '/../../../../bootstrap/start.php';
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
        return ($extra) ? new Document(new MichelfMarkdownExtra) : new Document(new MichelfMarkdown());
    }
}