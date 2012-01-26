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
 * Node
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Node implements NodeInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * Sets the key.
     *
     * @param string $key The key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Gets the key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the value.
     *
     * @param string $value The value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Gets the value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
