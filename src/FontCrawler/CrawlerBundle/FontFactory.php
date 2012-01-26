<?php

namespace FontCrawler\CrawlerBundle;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

use FontCrawler\CrawlerBundle\Util\Crawler as CssCrawler;
use FontCrawler\CrawlerBundle\Util\NodeCollection;
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
            $fonts = array_merge($fonts, $this->findFontFaces($resource)->toArray());
            $fonts = array_merge($fonts, $this->findFontFacesFromImportNodes($resource)->toArray());
        }

        return $fonts;
    }

    public function findFontFacesFromImportNodes(FileResource $fileResource)
    {
        $factory = $this;
        $fonts = new NodeCollection();

        $this->cssCrawler
            ->setInput($fileResource->getContent())
            ->filter('@import', function (NodeInterface $node) use ($fonts, $factory, $fileResource) {
                $locator      = $fileResource->getFileLocator();
                $resourcePath = $locator->find($node->getUrl());

                $response = $factory->browser->get($resourcePath);
                if (200 === $response->getStatusCode()) {
                    $resource = new FileResource($locator, $response->getContent());
                    $fonts->merge($factory->findFontFaces($resource));
                }
            });

        return $fonts;
    }

    public function findFontFaces(FileResource $fileResource)
    {
        $factory = $this;

        $fonts = $this->cssCrawler
            ->setInput($fileResource->getContent())
            ->filter('@font-face', function(NodeInterface $node) use ($factory, $fileResource) {
                $font = new Font();
                $font->setFontFamily($node->getFontFamily());
                $font->setFontStyle($node->getFontStyle());
                $font->setFontWeight($node->getFontWeight());

                $hasSources = true;
                foreach ($node->getSources() as $extension => $source) {
                    $locator      = $fileResource->getFileLocator();
                    $resourcePath = $locator->find($source);

                    $response = $factory->browser->get($resourcePath);
                    if (200 === $response->getStatusCode()) {
                        $font->addSource($extension, $resourcePath);
                    } else {
                        $hasSources = false;
                    }
                }

                if ($hasSources) {
                    return $font;
                }
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
