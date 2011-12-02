<?php

namespace FontCrawler\CrawlerBundle;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

use FontCrawler\CrawlerBundle\Crawler as CssCrawler;
use FontCrawler\CrawlerBundle\Node\NodeInterface;

class FontFactory
{
    public function __construct(DomCrawler $domCrawler, CssCrawler $cssCrawler)
    {
        $this->domCrawler = $domCrawler;
        $this->cssCrawler = $cssCrawler;
        $this->browser  = new Browser();
        
    }

    public function createFromHtml($input, $host, $fromFilsystem = false)
    {
        $this->domCrawler->clear();
        $this->domCrawler->addHtmlContent($input);

        $links = new \ArrayObject;

        $this->domCrawler->filter('link')->each(function ($node) use ($links) {
            $link = $node->getAttribute('href');
            $rel  = $node->getAttribute('rel');

            if ('stylesheet' == $rel) {
                $links[] = $link;
            }
        });

        $factory = $this;

        foreach ($links as $link) {
            $fileResource = $this->getLinkResource($link, $host, $fromFilsystem);
            if (!$fileResource) {
                continue;
            }

            $this->cssCrawler
                ->setInput($fileResource->getContent())
                ->filter('@font-face', function(NodeInterface $node) use ($factory, $host, $fromFilsystem) {
                    foreach ($node->getSrc() as $source) {
                        $source = trim($source, "'");
                        $source = $factory->getLinkResource($source, $host, $fromFilsystem);
                        echo($source->getPath()) . PHP_EOL;
                    }
                });

            //print_r($fileResource);
        }

        //return $links;
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

            return new FileResource($file, file_get_contents($file));
        }

        $request = Request::create($host);
        $host    = $request->getUriForPath('');

        $link = $host . '/' . $link;

        $response = $this->browser->get($link);
        $status   = $response->getStatusCode();

        if (200 === $status) {
            return new FileResource($link, $response->getContent());
        }
    }
}
