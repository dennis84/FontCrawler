<?php

namespace FontCrawler\CrawlerBundle\Tests;

use FontCrawler\CrawlerBundle\FontFactory;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;
use FontCrawler\CrawlerBundle\Crawler as CssCrawler;

class FontFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new FontFactory(new DomCrawler(), new CssCrawler());
    }

    public function testInitFactory()
    {
        $this->assertInstanceOf('FontCrawler\CrawlerBundle\FontFactory', $this->factory);
    }

    public function testCreateFromHtml()
    {
        $result = $this->factory->createFromHtml(
            $this->loadHtmlFile('index.html'),
            __DIR__.'/Fixures',
            true
        );

        print_r($result);
    }

    private function loadHtmlFile($name)
    {
        return file_get_contents(__DIR__.'/Fixures/'.$name);
    }
}