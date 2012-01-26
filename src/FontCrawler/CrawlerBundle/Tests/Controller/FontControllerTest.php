<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * FontControllerTest.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        //$this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }

    public function testSearchTermAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search/hello');
    }
}
