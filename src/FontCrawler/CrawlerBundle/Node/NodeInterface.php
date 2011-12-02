<?php

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