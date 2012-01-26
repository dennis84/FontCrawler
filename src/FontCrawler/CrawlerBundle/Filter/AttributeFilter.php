<?php

/*
 * This file is part of the gameop package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Filter;

use FontCrawler\CrawlerBundle\Util\NodeCollection;
use FontCrawler\CrawlerBundle\Node\Node;

/**
 * AttributeFilter.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class AttributeFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $selector;

    /**
     * Constructor.
     *
     * @param string $selector The css sesector
     */
    public function __construct($selector)
    {
        $this->selector = preg_quote($selector, FilterInterface::PREG_QUOTE_DELIMETER);
    }

    /**
     * {@inheritDoc}
     */
    public function filter($input)
    {
        preg_match_all(
            sprintf('#(?P<key>%s)\s*:(\s*(?P<value>.*?))[\}\;]#su', $this->selector),
            $input,
            $matches,
            PREG_SET_ORDER
        );

        $output = new NodeCollection();

        foreach ($matches as $match) {
            $node = new Node();
            $node->setKey($match['key']);
            $node->setValue(preg_replace('/[\"\']/', '', $match['value']));

            $output[] = $node;
        }

        return $output;
    }
}
