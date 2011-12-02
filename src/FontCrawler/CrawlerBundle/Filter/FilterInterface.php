<?php

namespace FontCrawler\CrawlerBundle\Filter;

/**
 * FilterInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
interface FilterInterface
{
    const PREG_QUOTE_DELIMETER = '#.+*~';

    /**
     * Filters the input
     *
     * @param string $input    The input string
     *
     * @return array
     */
    function filter($input);
}