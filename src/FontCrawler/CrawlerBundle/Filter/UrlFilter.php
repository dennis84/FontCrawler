<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Filter;

use FontCrawler\CrawlerBundle\Util\NodeCollection;
use FontCrawler\CrawlerBundle\Node\Url;

/**
 * UrlFilter.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class UrlFilter implements FilterInterface
{
    /**
     * {@inheritDoc}
     */
    public function filter($input)
    {
        preg_match_all(
            '#url\((?P<url>.*?)[\#\?\)]#u',
            $input,
            $matches,
            PREG_SET_ORDER
        );

        $output = new NodeCollection();

        foreach ($matches as $match) {
            $file = preg_replace('/[\"\']/', '', $match['url']);

            $node = new Url();
            $node->setKey('url');
            $node->setValue($file);
            $node->setExtension(pathinfo($file, PATHINFO_EXTENSION));

            $output[] = $node;
        }

        return $output;
    }
}
