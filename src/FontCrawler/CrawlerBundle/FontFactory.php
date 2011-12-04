<?php

namespace FontCrawler\CrawlerBundle;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

use FontCrawler\CrawlerBundle\Crawler as CssCrawler;
use FontCrawler\CrawlerBundle\Node\NodeInterface;
use FontCrawler\CrawlerBundle\Document\FontFace;

class FontFactory
{
    public function __construct(DomCrawler $domCrawler, CssCrawler $cssCrawler, Browser $browser)
    {
        $this->domCrawler = $domCrawler;
        $this->cssCrawler = $cssCrawler;
        $this->browser    = $browser;
    }

    public function createFromHtml($input, $host)
    {
        $this->domCrawler->clear();
        $this->domCrawler->addHtmlContent($input);

        $links     = $this->findLinks($this->domCrawler);
        $fontFaces = array();

        foreach ($links as $link) {
            $fileResource = $this->getLinkResource($link, $host);
            if (!$fileResource) {
                continue;
            }

            /*$fontFaces = array_merge(
                $fontFaces,
                $this->findFontFaces($fileResource)
            );*/
        }

        return $fontFaces;
    }

    public function getLinkResource($link, $host)
    {
        // The resource points from the webroot path.
        if (0 === strpos($link, '/')) {
            $path = $this->mergePaths($host, $link);
        }

        $request = Request::create($host);
        $host    = $request->getUriForPath('');

        $link = $host . '/' . $link;

        $response = $this->browser->get($link);
        $status   = $response->getStatusCode();

        if (200 === $status) {
            return new FileResource($link, $response->getContent(), $host);
        }
    }

    private function mergePaths($base, $source)
    {
        // Appends a trailing slash at the end
        // of each base path.
        if (strlen($base) - 1 !== strrpos($base, '/')) {
            $base .= '/';
        }

        return $base . $source;
    }

    private function findLinks(DomCrawler $crawler)
    {
        $links = $this->domCrawler->filter('link')->each(function ($node) {
            $link = $node->getAttribute('href');
            $rel  = $node->getAttribute('rel');

            if ('stylesheet' == $rel) {
                return $link;
            }
        });

        return array_filter($links);
    }

    private function findFontFaces(FileResource $fileResource)
    {
        $factory = $this;

        $fontFaces = $this->cssCrawler
            ->setInput($fileResource->getContent())
            ->filter('@font-face', function(NodeInterface $node) use ($factory, $fileResource) {
                $fontFace = new FontFace();
                $fontFace->setFontFamily($node->getFontFamily());
                $fontFace->setFontStyle($node->getFontStyle());
                $fontFace->setFontWeight($node->getFontWeight());

                foreach ($node->getSrc() as $source) {
                    $source = $factory->getLinkResource($source, $fileResource->getHost());

                    if (!$source) {
                        continue;
                    }

                    $fontFace->addSource($source->getPath());
                }

                return $fontFace;
            });

        return $fontFaces;
    }

    /**
     * Cleans a relative path. This will make a path:
     *
     * like: /var/www/demo/../demo/
     * to:   /var/www/demo/
     *
     * Note: The level of backwarding is 1 /var/www/demo/../../www
     *       will not supported at the moment.
     *
     * @param string $path The path to clean
     *
     * @return string
     */
    public function cleanRelativePath($path)
    {
        if (false !== strpos($path, '../..')) {
            throw new \InvalidArgumentException('You can go only one level backwards.');
        }

        return preg_replace('/\w+\/\.\.\//', '', $path);
    }
}
