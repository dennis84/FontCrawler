<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Node;

/**
 * NodeInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
interface NodeInterface
{
    /**
     * Sets the key.
     *
     * @param string $key The key
     */
    function setKey($key);

    /**
     * Gets the key.
     *
     * @return string
     */
    function getKey();

    /**
     * Sets the value.
     *
     * @param string $value The value
     */
    function setValue($value);

    /**
     * Gets the value.
     *
     * @return string
     */
    function getValue();
}
