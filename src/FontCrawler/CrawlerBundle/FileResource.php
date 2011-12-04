<?php

namespace FontCrawler\CrawlerBundle;

class FileResource
{
    protected $path;
    protected $content;
    protected $host;

    public function __construct($path, $content = '', $host)
    {
        $this->path    = $path;
        $this->content = $content;
        $this->host    = $host;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getHost()
    {
        return $this->host;
    }
}
