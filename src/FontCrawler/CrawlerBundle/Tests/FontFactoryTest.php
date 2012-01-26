<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Tests;

use FontCrawler\CrawlerBundle\FontFactory;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;
use FontCrawler\CrawlerBundle\Util\Crawler as CssCrawler;
use Buzz\Browser;

/**
 * FontFactoryTest.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new FontFactory(
            new DomCrawler(),
            new CssCrawler(),
            new Browser()
        );
    }

    public function testInitFactory()
    {
        $this->assertInstanceOf('FontCrawler\CrawlerBundle\FontFactory', $this->factory);
    }

    public function testCreateFromHtml()
    {
        $fonts = $this->factory->createFromHtml(
            $this->loadHtmlFile('index.html'),
            'http://test.fontcrawler.com/'
        );

        $this->assertEquals(2, count($fonts));
    }

    private function loadHtmlFile($name)
    {
        $result = file_get_contents('http://test.fontcrawler.com/' . $name);

        if (false === $result) {
            $this->markTestIncomplete(
                'The test could not completed. Maybe you must set'.
                'the test enviroment in your web server configuration.'
            );
        }

        return $result;
    }
}
