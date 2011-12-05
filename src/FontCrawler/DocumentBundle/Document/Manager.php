<?php

namespace FontCrawler\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * DocumentManager.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Manager
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param DocumentManager $documentManager The doctrine document manager
     * @param string          $class           The class
     */
    public function __construct(DocumentManager $documentManager, $class)
    {
        $this->documentManager = $documentManager;
        $this->repository      = $documentManager->getRepository($class);

        $this->setClass($class);
    }

    /**
     * Sets the offer class name.
     *
     * @param string $class The offer class name
     */
    public function setClass($class)
    {
        $metadata    = $this->documentManager->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * Gets the class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Gets the doctrine document manager.
     *
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     * Gets the repository.
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Creates a query builder.
     *
     * @return Query\Builder
     */
    public function createQueryBuilder()
    {
        return $this->repository->createQueryBuilder();
    }
}
