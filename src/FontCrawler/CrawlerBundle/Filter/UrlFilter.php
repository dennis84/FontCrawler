<?php

namespace FontCrawler\CrawlerBundle\Filter;

use FontCrawler\CrawlerBundle\Node\Url;

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
