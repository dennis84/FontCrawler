<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Font.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * @MongoDB\Document(
 *   collection="fonts"
 * )
 */
class Font
{
    /**
     * Unique ID.
     *
     * @var string
     * @MongoDB\Id()
     */
    protected $id;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $fontFamily;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $fontWeight;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $fontStyle;

    /**
     * @var array
     * @MongoDB\Field(type="hash")
     */
    protected $sources = array();

    /**
     * Gets the font id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the font family.
     *
     * @param string $fontFamily The font family
     */
    public function setFontFamily($fontFamily)
    {
        $this->fontFamily = $fontFamily;
    }

    /**
     * Gets the font family.
     *
     * @return string
     */
    public function getFontFamily()
    {
        return $this->fontFamily;
    }

    /**
     * Sets the font weight.
     *
     * @param string $fontWeight The font weight
     */
    public function setFontWeight($fontWeight)
    {
        $this->fontWeight = $fontWeight;
    }

    /**
     * Gets the font weight.
     *
     * @return string
     */
    public function getFontWeight()
    {
        return $this->fontWeight;
    }

    /**
     * Sets the font style.
     *
     * @param string $fontStyle The font style
     */
    public function setFontStyle($fontStyle)
    {
        $this->fontStyle = $fontStyle;
    }

    /**
     * Gets the font style.
     *
     * @return string
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Sets the font sources.
     *
     * @param array $sources The font sources
     */
    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Adds a font source.
     *
     * @param string $source The source
     */
    public function addSource($extension, $source)
    {
        $this->sources[$extension] = $source;
    }

    /**
     * Gets the font sources.
     *
     * @return array
     */
    public function getSources()
    {
        return $this->sources;
    }
}
