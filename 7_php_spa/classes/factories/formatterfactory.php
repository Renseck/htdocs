<?php

namespace factories;

use factories\baseFactory;

class formatterFactory extends baseFactory
{
    protected $formatterMap = [];

    // =============================================================================================
    public function __construct()
    {
        $this->formatterMap = [
            "html" => \formatters\htmlFormatter::class,
            "json" => \formatters\jsonFormatter::class,
            "xml" => \formatters\xmlFormatter::class,
            "default" => \formatters\plaintextFormatter::class
        ];     
    }

    // =============================================================================================
    public function create(string $type, array $params = [])
    {
        if (!$this->canCreate($type))
        {
            throw new \InvalidArgumentException("Unsupported formatter type: $type");
        }

        return new $this->formatterMap[$type](...$params);
    }

    // =============================================================================================
    public function canCreate(string $type)
    {
        return isset($this->formatterMap[$type]);
    }

    // =============================================================================================
}