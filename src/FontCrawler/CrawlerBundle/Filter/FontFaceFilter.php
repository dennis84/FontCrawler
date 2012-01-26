<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Filter;

use FontCrawler\CrawlerBundle\Node\NodeInterface;
use FontCrawler\CrawlerBundle\Node\FontFace;
use FontCrawler\CrawlerBundle\Util\Crawler;
use FontCrawler\CrawlerBundle\Util\NodeCollection;

/**
 * FontFaceFilter.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontFaceFilter implements FilterInterface
{
    /**
     * {@inheritDoc}
     */
    public function filter($input)
    {
        $output = new NodeCollection();

        $crawler = Crawler::create()
            ->setInput($input)
            ->filter(new RuleFilter('@font-face'), function (NodeInterface $node) use ($output) {
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

                $output[] = $fontFace;
            });

        return $output;
    }
}
