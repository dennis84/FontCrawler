<?php

namespace FontCrawler\CrawlerBundle;

class FileResource
{
    protected $path;
    protected $content;

    public function __construct($path, $content = '')
    {
        $this->path    = $path;
        $this->content = $content;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getContent()
    {
        return $this->content;
    }
}
