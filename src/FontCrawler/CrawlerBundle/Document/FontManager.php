<?php

namespace FontCrawler\CrawlerBundle\Document;

use FontCrawler\DocumentBundle\Document\Manager;

/**
 * FontManager.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontManager extends Manager
{
    /**
     * Creates a font.
     *
     * @return Font
     */
    public function createFont()
    {
        $class = $this->getClass();
        return new $class;
    }

    /**
     * Updates a font.
     *
     * @param Font $font The font object
     */
    public function updateFont(Font $font)
    {
        $this->documentManager->persist($font);
        $this->documentManager->flush();
    }

    /**
     * Deletes a font.
     *
     * @param Font $font The font object
     */
    public function deleteFile(DoctrineFile $file)
    {
        $this->documentManager->remove($file);
        $this->documentManager->flush();
    }

    /**
     * Finds all fonts.
     *
     * @return array
     */
    public function findFonts()
    {
        return $this->repository->findAll()->toArray();
    }

    /**
     * Finds a font by id.
     *
     * @param string $id The id
     *
     * @return Font
     */
    public function findFontById($id)
    {
        return $this->repository->find($id);
    }
}
