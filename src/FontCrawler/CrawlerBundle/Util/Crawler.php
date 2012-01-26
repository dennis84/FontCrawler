<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Util;

use FontCrawler\CrawlerBundle\Filter\FilterInterface;
use FontCrawler\CrawlerBundle\Filter\RuleFilter;
use FontCrawler\CrawlerBundle\Filter\AttributeFilter;
use FontCrawler\CrawlerBundle\Filter\FontFaceFilter;
use FontCrawler\CrawlerBundle\Filter\UrlFilter;
use FontCrawler\CrawlerBundle\Filter\ImportFilter;

/**
 * Crawler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Crawler
{
    /**
     * @var string
     */
    protected $input;

    /**
     * @var int
     */
    protected $nodeCount = 0;

    /**
     * Create a new crawler.
     */
    public static function create()
    {
        return new self;
    }

    /**
     * Loads a css from file.
     *
     * @param string $file The file path
     */
    public function loadResource($file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(
                sprintf('File "%s" not found', $file)
            );
        }

        $this->input = file_get_contents($file);

        return $this;
    }

    /**
     * Sets the css input.
     *
     * @param string $input The css input
     */
    public function setInput($input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Gets the input.
     *
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Starts the filter.
     *
     * The filter can be an instanceof FilterInterface
     * or a string which will try to guess the right Filter
     * automaticly.
     *
     * @param mixed   $filter  The filter object
     * @param Closure $closure The callback function as closure
     *
     * @return array
     *
     * @throws InvalidArgumentException If the passed filter does not exists
     */
    public function filter($filter, \Closure $closure)
    {
        if (is_string($filter)) {
            $filter = $this->guessFilter($filter);
        }

        if (!$filter instanceof FilterInterface) {
            throw new \InvalidArgumentException('The filter does not exists.');
        }

        $return = new NodeCollection();
        $nodes  = $filter->filter($this->input);

        if (0 === $nodes->count()) {
            return new NodeCollection();
        }

        foreach ($nodes as $node) {
            $crawler = $this::create();
            $crawler->setInput($node->getValue());

            $result = $closure($node, $crawler);
            if (null !== $result) {
                $return[] = $result;
            }
        }

        $this->nodeCount = count($nodes);

        return $return;
    }

    /**
     * Gets the count of found nodes.
     *
     * @return int
     */
    public function count()
    {
        return $this->nodeCount;
    }

    /**
     * Guesses the filter by string.
     *
     * @param string $filter The named filter
     *
     * @return FilterInterface|null
     */
    protected function guessFilter($filter)
    {
        if (0 === strpos($filter, '.') || 0 === strpos($filter, '#')) {
            return new RuleFilter($filter);
        }

        if (0 === strpos($filter, '@font-face')) {
            return new FontFaceFilter();
        }

        if (0 === strpos($filter, '@import')) {
            return new ImportFilter();
        }
    }
}
