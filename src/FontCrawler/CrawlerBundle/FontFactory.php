<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

/**
 * FontFactory.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontFactory
{
    /**
     * @var DomCrawler
     */
    protected $domCrawler;

    /**
     * @var CssCrawler
     */
    protected $cssCrawler;

    /**
     * @var FileLocator
     */
    protected $fileLocator;

    /**
     * @var Browser
     */
    protected $browser;

    /**
     * Constructor.
     *
     * @param DomCrawler  $domCrawler  The dom crawler
     * @param CssCrawler  $cssCrawler  The css crawler
     * @param FileLocator $fileLocator The file locator
     * @param Browser     $browser     The browser
     */
    public function __construct(
        DomCrawler $domCrawler,
        CssCrawler $cssCrawler,
        FileLocator $fileLocator,
        Browser $browser
    ) {
        $this->domCrawler  = $domCrawler;
        $this->cssCrawler  = $cssCrawler;
        $this->fileLocator = $fileLocator;
        $this->browser     = $browser;
    }

    /**
     * Creates fonts from host's html.
     *
     * @param string $input The html input
     * @param string $host  The host address
     *
     * @return array
     */
    public function createFromHtml($input, $host)
    {
        $this->domCrawler->clear();
        $this->domCrawler->addHtmlContent($input);
        $this->fileLocator->setBasePath($host);

        $links = $this->findLinks($this->domCrawler);
        $fonts = array();

        foreach ($links as $link) {
            $resourcePath = $this->fileLocator->find($link, true);

            $response = $this->browser->get($resourcePath);
            if (200 !== $response->getStatusCode()) {
                continue;
            }

            $resource = new FileResource($this->fileLocator, $response->getContent());
            $fonts = array_merge($fonts, $this->findFontFaces($resource)->toArray());
            $fonts = array_merge($fonts, $this->findFontFacesFromImportNodes($resource)->toArray());
        }

        return $fonts;
    }

    /**
     * Find font faces from import nodes.
     *
     * @param FileResource $fileResource The file resource
     *
     * @return NodeCollection
     */
    public function findFontFacesFromImportNodes(FileResource $fileResource)
    {
        $factory = $this;
        $fonts = new NodeCollection();

        $this->cssCrawler
            ->setInput($fileResource->getContent())
            ->filter('@import', function (NodeInterface $node) use ($fonts, $factory, $fileResource) {
                $locator      = $fileResource->getFileLocator();
                $resourcePath = $locator->find($node->getUrl());

                $response = $factory->getBrowser()->get($resourcePath);
                if (200 === $response->getStatusCode()) {
                    $resource = new FileResource($locator, $response->getContent());
                    $fonts->merge($factory->findFontFaces($resource));
                }
            });

        return $fonts;
    }

    /**
     * Find font faces.
     *
     * @param FileResource $fileResource The file resource
     *
     * @return NodeCollection
     */
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

                    $response = $factory->getBrowser()->get($resourcePath);
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

    /**
     * Find stylesheet link in html.
     *
     * @param DomCrawler $crawler The dom crawler
     *
     * @return array
     */
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

    /**
     * Gets the browser component.
     *
     * @return Browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }
}
