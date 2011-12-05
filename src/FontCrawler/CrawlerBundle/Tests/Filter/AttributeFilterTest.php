<?php

namespace FontCrawler\CrawlerBundle\Test\Filter;

use FontCrawler\CrawlerBundle\Crawler;
use FontCrawler\CrawlerBundle\Filter\RuleFilter;
use FontCrawler\CrawlerBundle\Filter\AttributeFilter;
use FontCrawler\CrawlerBundle\Filter\FontFaceFilter;
use FontCrawler\CrawlerBundle\Filter\UrlFilter;
use FontCrawler\CrawlerBundle\Filter\FilterInterface;
use FontCrawler\CrawlerBundle\Node\NodeInterface;

class AttributeFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $crawler;
    public $baseNode;

    /**
     * Tests filter.
     */
    public function testFilter()
    {
        $test = $this;

        $crawler = Crawler::create();
        $crawler
            ->setInput($this->baseNode)
            ->filter(new AttributeFilter('background'), function (NodeInterface $node) use ($test) {
                $test->assertEquals('background', $node->getKey());
                $test->assertEquals('url(img.png) no-repeat', $node->getValue());
            });
        $this->assertEquals(1, $crawler->count());

        $crawler = Crawler::create();
        $crawler
            ->setInput($this->baseNode)
            ->filter(new AttributeFilter('color'), function (NodeInterface $node) use ($test) {
                $test->assertEquals('color', $node->getKey());
                $test->assertEquals('#ff0000', $node->getValue());
            });
        $this->assertEquals(1, $crawler->count());

        $crawler = Crawler::create();
        $crawler
            ->setInput($this->baseNode)
            ->filter(new AttributeFilter('text-align'), function (NodeInterface $node) use ($test) {
                $test->assertEquals('text-align', $node->getKey());
                $test->assertEquals('center', $node->getValue());
            });
        $this->assertEquals(1, $crawler->count());
    }

    protected function setUp()
    {
        $fileA = __DIR__ . '/../Fixures/css/style.css';
        $test  = $this;

        Crawler::create()
            ->setInput(file_get_contents($fileA))
            ->filter(new RuleFilter('#hello'), function (NodeInterface $node) use ($test) {
                $test->baseNode = $node->getValue();
            });
    }
}