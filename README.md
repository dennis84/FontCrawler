<?php

class FileLocator
{
    protected $basePath;
    protected $currentPath;
    protected $currentTarget;

    /**
     * Sets the base path and sets the 
     * current path to the same location at
     * the moment.
     *
     * @param string $basePath The base path
     */
    public function setBasePath($basePath)
    {
        $this->basePath    = $basePath;
        $this->currentPath = $basePath;
    }

    public function find($resource)
    {
        $this->guessCurrentDirectory($resource);
    }

    private function guessCurrentDirectory($resource)
    {
        // if first char of resource is "/"
        // navigates to the base dir.
        if (0 === strpos($resource, '/')) {
            $this->currentPath = $this->basePath;
        }

        // if the path navigates any directories
        // backwards.
        if (false !== strpos($resource, '../')) {
            $resource = preg_replace('/\w+\/\.\.\//', '', $resource);
        }
    }

    private function resourceIsFile($resource)
    {
        return substr(strrchr($resource, '.'), 1);
    }
}

$fileLocator = new FileLocator();
$fileLocator->setBasePath('http://example.com/');

// Finds at first time the file.
$fileLocator->find('css/style.css');

// Finds the file in last resource context.
$fileLocator->find('../img/background.png');



