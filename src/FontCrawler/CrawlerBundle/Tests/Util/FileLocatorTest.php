<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Tests\Util;

use FontCrawler\CrawlerBundle\Util\FileLocator;

/**
 * FileLocatorTest.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FileLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInitFileLocator()
    {
        $this->assertInstanceOf('FontCrawler\CrawlerBundle\Util\FileLocator', new FileLocator());
    }
    
    public function testFindOneFile()
    {
        $locator = new FileLocator();
        $locator->setBasePath('http://example.com/');
        $result = $locator->find('/css/style.css');

        $this->assertEquals('http://example.com/css/style.css', $result);
    }

    public function testFindMoreFiles()
    {
        $locator = new FileLocator();
        $locator->setBasePath('http://example.com/');

        $result = $locator->find('/css/style.css');
        $this->assertEquals('http://example.com/css/style.css', $result);

        $result = $locator->find('css/fonts.css');
        $this->assertEquals('http://example.com/css/fonts.css', $result);

        $result = $locator->find('css/tools/reset.css');
        $this->assertEquals('http://example.com/css/tools/reset.css', $result);

        $result = $locator->find('css/tools/../base.css');
        $this->assertEquals('http://example.com/css/base.css', $result);
    }

    public function testFindFilesWithPlunge()
    {
        $locator = new FileLocator();
        $locator->setBasePath('http://example.com/');

        $result = $locator->find('/css/style.css', true);
        $this->assertEquals('http://example.com/css/style.css', $result);

        $result = $locator->find('fonts.css', true);
        $this->assertEquals('http://example.com/css/fonts.css', $result);

        $result = $locator->find('/css/base.css', true);
        $this->assertEquals('http://example.com/css/base.css', $result);

        $result = $locator->find('../fonts/Helvetica.ttf', true);
        $this->assertEquals('http://example.com/fonts/Helvetica.ttf', $result);
    }

    public function testFindFilesByAbsolutePath()
    {
        $locator = new FileLocator();
        $locator->setBasePath('http://example.com/');
        
        $result = $locator->find('http://example.com/css/style.css');
        $this->assertEquals('http://example.com/css/style.css', $result);

        $result = $locator->find('http://example.com/css/fonts.css', true);
        $this->assertEquals('http://example.com/css/fonts.css', $result);

        $result = $locator->find('../fonts/Helvetica.ttf', true);
        $this->assertEquals('http://example.com/fonts/Helvetica.ttf', $result);
    }
}
