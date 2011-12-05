<?php

namespace FontCrawler\CrawlerBundle;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

use FontCrawler\CrawlerBundle\Util\Crawler as CssCrawler;
use FontCrawler\CrawlerBundle\Node\NodeInterface;
use FontCrawler\CrawlerBundle\Document\Font;
use FontCrawler\CrawlerBundle\Util\FileLocator;
use FontCrawler\CrawlerBundle\Util\FileResource;

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

        $links = $this->findLinks($this->domCrawler);
        $fonts = array();

        foreach ($links as $link) {
            $locator = new FileLocator();
            $locator->setBasePath($host);
            $resourcePath = $locator->find($link, true);

            $response = $this->browser->get($resourcePath);
            if (200 !== $response->getStatusCode()) {
                continue;
            }

            $resource = new FileResource($locator, $response->getContent());
            $fonts    = array_merge($fonts, $this->findFontFaces($resource));
        }

        return $fonts;
    }

    private function findFontFaces(FileResource $fileResource)
    {
        $factory = $this;

        $fonts = $this->cssCrawler
            ->setInput($fileResource->getContent())
            ->filter('@font-face', function(NodeInterface $node) use ($factory, $fileResource) {
                $font = new Font();
                $font->setFontFamily($node->getFontFamily());
                $font->setFontStyle($node->getFontStyle());
                $font->setFontWeight($node->getFontWeight());

                foreach ($node->getSources() as $extension => $source) {
                    $locator      = $fileResource->getFileLocator();
                    $resourcePath = $locator->find($source);

                    $response = $factory->browser->get($resourcePath);
                    if (200 !== $response->getStatusCode()) {
                        continue;
                    }

                    $font->addSource($extension, $resourcePath);
                }

                return $font;
            });

        return $fonts;
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

    public function getBrowser()
    {
        return $this->browser;
    }
}
