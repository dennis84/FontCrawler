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