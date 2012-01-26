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

use FontCrawler\CrawlerBundle\Util\NodeCollection;

/**
 * NodeCollectionTest.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class NodeCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $nodes = new NodeCollection();
        $nodes[] = 'Hello';
        $nodes[] = 'World';
    }
}
