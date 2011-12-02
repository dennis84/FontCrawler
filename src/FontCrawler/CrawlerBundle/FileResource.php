<?php

namespace FontCrawler\CrawlerBundle;

class FileResource
{
    protected $path;
    protected $content;
    protected $host;
    protected $fromFilsystem;

    public function __construct($path, $content = '', $host, $fromFilsystem = false)
    {
        $this->path          = $path;
        $this->content       = $content;
        $this->host          = $host;
        $this->fromFilsystem = $fromFilsystem;
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

    public function getFromFilesystem()
    {
        return $this->fromFilsystem;
    }
}
