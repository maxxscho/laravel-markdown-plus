<?php

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

    public function testHaHa()
    {
        $this->assertTrue(true);
    }
}