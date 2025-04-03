<?php

namespace factories;

use factories\baseFactory;
use config\pageConfig;

class viewFactory extends baseFactory
{
    private $pages;

    // =============================================================================================
    public function __construct()
    {
        $this->pages = pageConfig::getPages();
    }

    // =============================================================================================
    public function create(string $type, array $params = [])
    {
        if (!$this->canCreate($type))
        {
            throw new \InvalidArgumentException("Unsupported page type: $type");
        }

        return new $this->pages[$type](...$params);
    }

    // =============================================================================================
    public function canCreate(string $type)
    {
        return isset($this->pages[$type]);
    }

    // =============================================================================================
}