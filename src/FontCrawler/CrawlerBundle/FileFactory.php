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
use Buzz\Browser;

use FontCrawler\CrawlerBundle\Crawler as CssCrawler;

/**
 * FileFactory.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FileFactory
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
     * @var Browser
     */
    protected $browser;

    /**
     * Constructor.
     *
     * @param Crawler $crawler The css crawler
     */
    public function __construct(DomCrawler $domCrawler, CssCrawler $cssCrawler, Browser $browser)
    {
        $this->domCrawler = $domCrawler;
        $this->cssCrawler = $cssCrawler;
        $this->browser    = $browser;
    }

    /**
     * Creates css files from html content.
     *
     * @param string $content The html content
     * @param string $host    The host
     *
     * @return array
     */
    public function createFromContent($content, $host)
    {
        $domCrawler = $this->domCrawler;
        $domCrawler->clear();
        $domCrawler->addContent($content);

        $links = $this->findLinks($domCrawler);

        foreach ($links as $link) {
            $content = $this->browser->get($host. $link);

            $this->cssCrawler->setInput($content);
            $output = $this->cssCrawler->filter('fontface');
        }

        //echo '<pre>'; print_r($links); echo '</pre>';
    }

    /**
     * Creates css fles from dom crawler.
     *
     * @param DomCrawler $domCrawler The dom crawler
     *
     * @return array
     */
    public function findLinks(DomCrawler $domCrawler)
    {
        $links = $domCrawler->filter('link')->each(function ($node) {
            $link = $node->getAttribute('href');
            $rel  = $node->getAttribute('rel');

            if ('stylesheet' == $rel) {
                return $link;
            }
        });

        return $links;
    }
}