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

/**
 * NodeCollection.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class NodeCollection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $elements = array();

    /**
     * Constructor.
     *
     * @param array $elements The node elements
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
    }

    /**
     * Adds a node with name and value.
     *
     * @param string|int $name  The node name
     * @param mixed      $value The node
     */
    public function add($name, $value)
    {
        $this->elements[$name] = $value;
    }

    public function get($name)
    {
        return $this->elements[$name];
    }

    public function has($name)
    {
        return array_key_exists($name, $elements);
    }

    public function remove($name)
    {
        unset($this->elements[$name]);
    }

    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $offset = $this->count();
        }

        $this->add($offset, $value);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function count()
    {
        return count($this->elements);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    public function toArray()
    {
        return $this->elements;
    }

    public function merge(NodeCollection $collection)
    {
        $this->elements = array_merge($this->elements, $collection->toArray());
    }

    public function filter(\Closure $closure)
    {
        return new NodeCollection(array_filter($this->elements, $closure));
    }
}
