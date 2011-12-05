<?php

namespace FontCrawler\CrawlerBundle;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

use FontCrawler\CrawlerBundle\Crawler as CssCrawler;
use FontCrawler\CrawlerBundle\Node\NodeInterface;
use FontCrawler\CrawlerBundle\Document\FontFace;
use FontCrawler\CrawlerBundle\Util\FileLocator;

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
            $locator = new FileLocator();
            $locator->setBasePath($host);
            $resourcePath = $locator->find($link, true);

            $response = $this->browser->get($resourcePath);
            $status   = $response->getStatusCode();
    
            if (200 !== $status) {
                continue;
            }

            $fileResource = new FileResource($locator, $response->getContent());

            $fontFaces = array_merge(
                $fontFaces,
                $this->findFontFaces($fileResource)
            );
        }

        return $fontFaces;
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
                    $locator      = $fileResource->getFileLocator();
                    $resourcePath = $locator->find($source);

                    $fontFace->addSource($resourcePath);
                }

                return $fontFace;
            });

        return $fontFaces;
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
}
