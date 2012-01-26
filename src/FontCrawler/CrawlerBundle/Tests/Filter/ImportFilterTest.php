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
use FontCrawler\CrawlerBundle\Filter\ImportFilter;
use FontCrawler\CrawlerBundle\Filter\FilterInterface;
use FontCrawler\CrawlerBundle\Node\NodeInterface;

/**
 * ImportFilterTest.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ImportFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests filter.
     */
    public function testFilter()
    {
        $file = __DIR__ . '/../Fixures/css/style.css';
        $test = $this;

        $crawler = Crawler::create()
            ->setInput(file_get_contents($file))
            ->filter(new ImportFilter(), function (NodeInterface $node) use ($test) {
                $expected = array(
                    'fonts.css',
                    'tools/fonts.css',
                );

                $test->assertTrue(in_array($node->getUrl(), $expected));
            });
    }
}
