<?php

namespace FontCrawler\CrawlerBundle\Test\Filter;

use FontCrawler\CrawlerBundle\Crawler;
use FontCrawler\CrawlerBundle\Filter\RuleFilter;
use FontCrawler\CrawlerBundle\Filter\AttributeFilter;
use FontCrawler\CrawlerBundle\Filter\FontFaceFilter;
use FontCrawler\CrawlerBundle\Filter\UrlFilter;
use FontCrawler\CrawlerBundle\Filter\FilterInterface;
use FontCrawler\CrawlerBundle\Node\NodeInterface;

class FontFaceFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests filter for id.
     */
    public function testFilter()
    {
        $test = $this;

        $crawler = Crawler::create()
            ->setInput(file_get_contents($this->fileA))
            ->filter(new FontFaceFilter(), function (NodeInterface $node) use ($test) {
                $test->assertEquals('@font-face', $node->getKey());
                $test->assertTrue(is_array($node->getSources()));
            });
    }

    protected function setUp()
    {
        $this->fileA   = __DIR__ . '/../Fixures/css/fonts.css';
    }
}