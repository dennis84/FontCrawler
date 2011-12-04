<?php

namespace FontCrawler\CrawlerBundle;

use Buzz\Browser;

/**
 * PathResolver.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class PathResolver
{
    protected $paths = array();
    protected $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function clear()
    {
        $this->paths = array();
    }

    public function addPath($path)
    {
        $this->paths[] = $path;
    }

    public function resolve($resource)
    {
        echo dirname($resource);

        foreach ($this->paths as $path) {
            $link   = $path . $resource;
            $result = $this->browser->get($link);

            if (200 === $result->getStatusCode()) {
                return $link;
            }
        }
    }
}
