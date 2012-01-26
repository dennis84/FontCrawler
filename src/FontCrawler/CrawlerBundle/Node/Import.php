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
 * Import.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Import extends Node
{
    /**
     * @var string
     */
    protected $url;

    /**
     * Sets the url.
     *
     * @param string $url The url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Gets the url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
