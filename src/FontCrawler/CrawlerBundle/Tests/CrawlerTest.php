<?php

namespace FontCrawler\CrawlerBundle\Test;

use FontCrawler\CrawlerBundle\Crawler;
use FontCrawler\CrawlerBundle\Filter\RuleFilter;
use FontCrawler\CrawlerBundle\Filter\AttributeFilter;
use FontCrawler\CrawlerBundle\Filter\FontFaceFilter;
use FontCrawler\CrawlerBundle\Filter\UrlFilter;
use FontCrawler\CrawlerBundle\Filter\FilterInterface;
use FontCrawler\CrawlerBundle\Node\NodeInterface;

class CrawlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests set input.
     */
    public function testSetInput()
    {
        $input   = file_get_contents($this->fileA);
        $crawler = Crawler::create()
            ->setInput($input);

        $this->assertEquals($input, $crawler->getInput());
    }

    /**
     * Tests loading a file.
     */
    public function testLoadResource()
    {
        $crawler = Crawler::create()
            ->loadResource($this->fileA);

        $this->assertEquals(file_get_contents($this->fileA), $crawler->getInput());
    }

    /**
     * Tests loading a non existing file.
     *
     * @expectedException InvalidArgumentException
     */
    public function testLoadResourceWithInvalidFile()
    {
        Crawler::create()
            ->loadResource('Fixures/css/foo.css');
    }

    /**
     * Tests the rule filter.
     */
    public function testFilterRule()
    {
        $test = $this;

        Crawler::create()
            ->setInput(file_get_contents($this->fileB))
            ->filter(new RuleFilter('#hello'), function (NodeInterface $node) use ($test) {
                $test->assertEquals('#hello', $node->getKey());
            });
    }

    /**
     * Test a guessed filter.
     */
    public function testGuessedFilter()
    {
        $test = $this;

        Crawler::create()
            ->setInput(file_get_contents($this->fileB))
            ->filter('#hello', function(NodeInterface $node) use ($test) {
                $test->assertEquals('#hello', $node->getKey());
            });

        Crawler::create()
            ->setInput(file_get_contents($this->fileA))
            ->filter('@font-face', function(NodeInterface $node) use ($test) {
                $test->assertEquals('@font-face', $node->getKey());
            });
    }

    /**
     * Test a guessed filter.
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidGuessedFilter()
    {
        Crawler::create()
            ->setInput(file_get_contents($this->fileB))
            ->filter('bla', function(NodeInterface $node) {
            });
    }

    protected function setUp()
    {
        $this->fileA   = __DIR__ . '/Fixures/css/fonts.css';
        $this->fileB   = __DIR__ . '/Fixures/css/style.css';
        $this->fileC   = __DIR__ . '/Fixures/css/fonts-compressed.css';
    }
}