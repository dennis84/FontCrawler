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
 * RuleFilterTest.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RuleFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $crawler;

    /**
     * Tests filter for id.
     */
    public function testFilterForId()
    {
        $test = $this;

        $this->crawler
            ->setInput(file_get_contents($this->fileA))
            ->filter(new RuleFilter('#hello'), function (NodeInterface $node) use ($test) {
                $test->assertEquals('#hello', $node->getKey());
            });
    }

    /**
     * Tests filter for class.
     */
    public function testFilterForClass()
    {
        $test  = $this;

        $this->crawler
            ->setInput(file_get_contents($this->fileA))
            ->filter(new RuleFilter('.foo'), function (NodeInterface $node) use ($test) {
                $test->assertEquals('.foo', $node->getKey());
            });

        $this->assertEquals(2, $this->crawler->count());
    }

    protected function setUp()
    {
        $this->crawler = new Crawler();
        $this->fileA   = __DIR__ . '/../Fixures/css/style.css';
    }
}
