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
 * Url node.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Url extends Node
{
    /**
     * @var string
     */
    protected $extension;

    /**
     * Sets the file extension.
     *
     * @param string $extension The file extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Gets the file extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
