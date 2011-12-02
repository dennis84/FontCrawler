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

/**
 * FontFace.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontFace
{
    /**
     * @var string
     */
    protected $fontFamily;

    /**
     * @var string
     */
    protected $fontWeight;

    /**
     * @var string
     */
    protected $fontStyle;

    /**
     * @var array
     */
    protected $sources = array();

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
     * @param array $src The font sources
     */
    public function setSrc(array $src)
    {
        $this->sources = $src;
    }

    /**
     * Adds a file to font sources.
     *
     * @param string $file The source file
     */
    public function addSources($file)
    {
        $this->sources[] = $file;
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
