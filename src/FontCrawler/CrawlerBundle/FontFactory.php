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

    public function createFromHtml($input, $host, $fromFilsystem = false)
    {
        $this->domCrawler->clear();
        $this->domCrawler->addHtmlContent($input);

        $links     = $this->findLinks($this->domCrawler);
        $fontFaces = array();

        foreach ($links as $link) {
            $fileResource = $this->getLinkResource($link, $host, $fromFilsystem);
            if (!$fileResource) {
                continue;
            }

            $fontFaces = array_merge(
                $fontFaces,
                $this->findFontFaces($fileResource)
            );
        }

        return $fontFaces;
    }

    public function getLinkResource($link, $host, $fromFilsystem = false)
    {
        if (0 === strpos($link, '/')) {
            $link = substr($link, 1);
        }

        if ($fromFilsystem) {
            $file = $host . '/' . $link;
            if (!file_exists($file)) {
                throw new \InvalidArgumentException(
                    sprintf('The file "%s" for link "%s" does not exists.', $file, $link)
                );
            }

            return new FileResource($file, file_get_contents($file), $host, $fromFilsystem);
        }

        $request = Request::create($host);
        $host    = $request->getUriForPath('');

        $link = $host . '/' . $link;

        $response = $this->browser->get($link);
        $status   = $response->getStatusCode();

        if (200 === $status) {
            return new FileResource($link, $response->getContent(), $host, $fromFilsystem);
        }
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
                    $source = trim($source, "'");
                    $source = $factory->getLinkResource(
                        $source,
                        $fileResource->getHost(),
                        $fileResource->getFromFilesystem()
                    );

                    if (!$source) {
                        continue;
                    }

                    $fontFace->addSource($source->getPath());
                }

                return $fontFace;
            });

        return $fontFaces;
    }
}
