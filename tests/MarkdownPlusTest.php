<?php

use Maxxscho\LaravelMarkdownPlus\MarkdownPlus;

class MarkdownPlusTest extends \Illuminate\Foundation\Testing\TestCase
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



    public function testMake()
    {
        $mp = new MarkdownPlus();

        $source = file_get_contents(__DIR__ . '/fixtures/document_01.md');

        $document = $mp->make($source);

        $this->assertInstanceOf('Maxxscho\LaravelMarkdownPlus\Document', $document);
        $this->assertEquals('Testdocument 01', $document->title());
    }



    public function testParseSectionCanParseOffset()
    {
        $mp = new MarkdownPlus();
        $f = "first\n-------\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals('second', $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseCanHandleExtraNewLines()
    {
        $mp = new MarkdownPlus();
        $f = "first\n\n\n-------\n\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals('second', $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseSectionCanHandleMultipleNewLines()
    {
        $mp = new MarkdownPlus();
        $f = "first\n\n\n-----\n--\n\n\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals("--\n\n\nsecond", $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseSectionBreaksWithoutNewLines()
    {
        $this->setExpectedException('Maxxscho\LaravelMarkdownPlus\Exceptions\TooFewSectionsException');
        $mp = new MarkdownPlus();
        $f = "first----second";
        $this->invokeMethod($mp, 'parseSection', [$f, 0]);
    }



    public function testParseSectionLessThanThreeDashes()
    {
        $this->setExpectedException('Maxxscho\LaravelMarkdownPlus\Exceptions\TooFewSectionsException');
        $mp = new MarkdownPlus();
        $f = "first\n--\nsecond";
        $this->invokeMethod($mp, 'parseSection', [$f, 0]);
    }



    public function testParseSecionnWithManyDashes()
    {
        $mp = new MarkdownPlus();
        $f = "first\n------------------------\nsecond";

        $this->assertEquals('first', $this->invokeMethod($mp, 'parseSection', [$f, 0]));
        $this->assertEquals('second', $this->invokeMethod($mp, 'parseSection', [$f, 1]));
    }



    public function testParseHeader()
    {
        $mp = new MarkdownPlus();
        $f = file_get_contents(__DIR__ . '/fixtures/document_02.md');

        $this->assertEquals('title: Testdocument02', $this->invokeMethod($mp, 'parseHeader', [$f]));
    }



    public function testParseContent()
    {
        $mp = new MarkdownPlus();
        $f = file_get_contents(__DIR__ . '/fixtures/document_02.md');

        $this->assertEquals('This is the content', $this->invokeMethod($mp, 'parseContent', [$f]));
    }



    public function testParseMeta()
    {
        $mp     = new MarkdownPlus();
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
}