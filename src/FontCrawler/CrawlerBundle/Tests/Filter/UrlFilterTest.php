<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Test\Filter;

use FontCrawler\CrawlerBundle\Util\Crawler;
use FontCrawler\CrawlerBundle\Filter\RuleFilter;
use FontCrawler\CrawlerBundle\Filter\AttributeFilter;
use FontCrawler\CrawlerBundle\Filter\FontFaceFilter;
use FontCrawler\CrawlerBundle\Filter\UrlFilter;
use FontCrawler\CrawlerBundle\Filter\FilterInterface;
use FontCrawler\CrawlerBundle\Node\NodeInterface;

/**
 * UrlFilterTest.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class UrlFilterTest extends \PHPUnit_Framework_TestCase
{
    public $baseNode;

    /**
     * Tests filter.
     */
    public function testFilter()
    {
        $test = $this;

        $crawler = Crawler::create()
            ->setInput($this->baseNode)
            ->filter(new UrlFilter(), function (NodeInterface $node) use ($test) {
                $test->assertEquals('url', $node->getKey());
                $test->assertEquals('img.png', $node->getValue());
            });
    }

    protected function setUp()
    {
        $file = __DIR__ . '/../Fixures/css/style.css';
        $test = $this;

        Crawler::create()
            ->setInput(file_get_contents($file))
            ->filter(new RuleFilter('\#hello'), function (NodeInterface $node) use ($test) {
                $test->baseNode = $node->getValue();
            });

        Crawler::create()
            ->setInput($this->baseNode)
            ->filter(new AttributeFilter('background'), function (NodeInterface $node) use ($test) {
                $test->baseNode = $node->getValue();
            });
    }
}
