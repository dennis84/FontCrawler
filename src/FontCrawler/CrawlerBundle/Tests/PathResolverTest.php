<?php

namespace FontCrawler\CrawlerBundle\Tests;

use FontCrawler\CrawlerBundle\PathResolver;
use Buzz\Browser;

class PathResolverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->resolver = new PathResolver(new Browser());
    }

    public function testInitPathResolver()
    {
        $this->assertInstanceOf('FontCrawler\CrawlerBundle\PathResolver', $this->resolver);
    }

    public function testWorkflow()
    {
        $this->resolver->clear();
        $this->resolver->addPath('http://test.fontcrawler.com/');
        $path = $this->resolver->resolve('css/style.css');

        $this->assertEquals('http://test.fontcrawler.com/css/style.css', $path);

        $this->resolver->clear();
        $this->resolver->addPath('http://test.fontcrawler.com');
        //$path = $this->resolver->resolve('css/style.css');

        //$this->assertEquals('http://test.fontcrawler.com/css/style.css', $path);
        
        
    }
}
