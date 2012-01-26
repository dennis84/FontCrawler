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

use FontCrawler\CrawlerBundle\Util\Crawler;
use FontCrawler\CrawlerBundle\Util\NodeCollection;
use FontCrawler\CrawlerBundle\Node\Import;
use FontCrawler\CrawlerBundle\Filter\UrlFilter;
use FontCrawler\CrawlerBundle\Node\NodeInterface;

/**
 * ImportFilter.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ImportFilter implements FilterInterface
{
    /**
     * {@inheritDoc}
     */
    public function filter($input)
    {
        preg_match_all(
            '#@import\s+(.*);#u',
            $input,
            $matches,
            PREG_SET_ORDER
        );

        $output = new NodeCollection();

        foreach ($matches as $match) {
            Crawler::create()
                ->setInput($match[1])
                ->filter(new UrlFilter(), function (NodeInterface $node) use ($output) {
                    $import = new Import();
                    $import->setKey('@import');
                    $import->setUrl($node->getValue());

                    $output[] = $import;
                });
        }

        return $output;
    }
}
