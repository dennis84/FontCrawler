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

use FontCrawler\CrawlerBundle\Node\NodeInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * NodeCollection.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class NodeCollection extends ArrayCollection
{
    /**
     * Merges two collections.
     *
     * @param NodeCollection $collection The collection to merge
     */
    public function merge(NodeCollection $collection)
    {
        $nodes = $this;

        $collection->forAll(function ($key, $value) use ($nodes) {
            $nodes[] = $value;
            return true;
        });
    }
}
