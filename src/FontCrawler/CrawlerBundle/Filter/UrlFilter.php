<?php

namespace FontCrawler\CrawlerBundle\Filter;

use FontCrawler\CrawlerBundle\Node\Node;

/**
 * UrlFilter.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class UrlFilter implements FilterInterface
{
    /**
     * Filters the input
     *
     * @param string $input The input string
     *
     * @return array
     */
    public function filter($input)
    {
        preg_match_all(
            '#url\((?P<url>.*?)[\#\?\)]#u',
            $input,
            $matches,
            PREG_SET_ORDER
        );

        $output = array();

        foreach ($matches as $match) {
            $node = new Node();
            $node->setKey('url');
            $node->setValue($match['url']);

            $output[] = $node;
        }

        return $output;
    }
}
