<?php

namespace FontCrawler\CrawlerBundle\Filter;

use FontCrawler\CrawlerBundle\Node\Node;

/**
 * RuleFilter.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RuleFilter implements FilterInterface
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
     * Filters the input
     *
     * @param string $input The input string
     *
     * @return array
     */
    public function filter($input)
    {
        preg_match_all(
            sprintf('#(?P<key>%s)\s*\{(\s*(?P<value>.*?))\}#su', $this->selector),
            $input,
            $matches,
            PREG_SET_ORDER
        );

        $output = array();

        foreach ($matches as $match) {
            $node = new Node();
            $node->setKey($match['key']);
            $node->setValue($match['value']);

            $output[] = $node;
        }

        return $output;
    }
}
