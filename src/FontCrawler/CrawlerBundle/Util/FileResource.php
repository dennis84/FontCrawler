<?php

namespace FontCrawler\CrawlerBundle\Util;

use FontCrawler\CrawlerBundle\Util\FileLocator;

class FileResource
{
    protected $fileLocator;
    protected $content;

    public function __construct(FileLocator $fileLocator, $content = '')
    {
        $this->fileLocator = $fileLocator;
        $this->content     = $content;
    }

    public function getFileLocator()
    {
        return $this->fileLocator;
    }

    public function getContent()
    {
        return $this->content;
    }
}
