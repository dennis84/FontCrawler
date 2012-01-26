<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Util;

use FontCrawler\CrawlerBundle\Util\FileLocator;

/**
 * FileResource.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FileResource
{
    /**
     * @var FileLocator
     */
    protected $fileLocator;

    /**
     * @var string
     */
    protected $content;

    /**
     * Constructor.
     *
     * @param FileLocator $fileLocator The file locator
     * @param string      $content     The content
     */
    public function __construct(FileLocator $fileLocator, $content = '')
    {
        $this->fileLocator = $fileLocator;
        $this->content     = $content;
    }

    /**
     * Gets the file locator.
     *
     * @return FileLocator
     */
    public function getFileLocator()
    {
        return $this->fileLocator;
    }

    /**
     * Gets the content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
