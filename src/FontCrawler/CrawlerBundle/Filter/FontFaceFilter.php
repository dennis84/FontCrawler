<?php

namespace FontCrawler\CrawlerBundle\Filter;

use FontCrawler\CrawlerBundle\Node\NodeInterface;
use FontCrawler\CrawlerBundle\Node\FontFace;
use FontCrawler\CrawlerBundle\Util\Crawler;

/**
 * FontFaceFilter.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontFaceFilter implements FilterInterface
{
    /**
     * @var array
     */
    protected $nodes;

    /**
     * Filters the input
     *
     * @param string $input The input string
     *
     * @return array
     */
    public function filter($input)
    {
        $this->output  = array();
        $filter = $this;

        $crawler = Crawler::create()
            ->setInput($input)
            ->filter(new RuleFilter('@font-face'), function (NodeInterface $node) use ($filter) {
                $fontFace = new FontFace();
                $fontFace->setKey($node->getKey());
                $fontFace->setValue($node->getValue());

                Crawler::create()
                    ->setInput($node->getValue())
                    ->filter(new AttributeFilter('font-family'), function (NodeInterface $node) use ($fontFace) {
                        $fontFace->setFontFamily($node->getValue());
                    });

                Crawler::create()
                    ->setInput($node->getValue())
                    ->filter(new AttributeFilter('font-style'), function (NodeInterface $node) use ($fontFace) {
                        $fontFace->setFontStyle($node->getValue());
                    });

                Crawler::create()
                    ->setInput($node->getValue())
                    ->filter(new AttributeFilter('font-weight'), function (NodeInterface $node) use ($fontFace) {
                        $fontFace->setFontWeight($node->getValue());
                    });

                Crawler::create()
                    ->setInput($node->getValue())
                    ->filter(new UrlFilter(), function (NodeInterface $node) use ($fontFace) {
                        $fontFace->addSource($node->getExtension(), $node->getValue());
                    });

                $filter->addNode($fontFace);
            });

        return $this->nodes;
    }

    /**
     * Adds a note to the collection.
     *
     * @param NodeInterface $node The node object
     */
    public function addNode(NodeInterface $node)
    {
        $this->nodes[] = $node;
    }
}
