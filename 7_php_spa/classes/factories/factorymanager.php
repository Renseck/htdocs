<?php

namespace factories;

class factoryManager
{
    private array $factories = [];
    
    private static ?factoryManager $instance = null;

    // ==================================== Singleton creator ======================================
    public static function getInstance() : self
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // =============================================================================================
    public function getFactory(string $type)
    {
        if (!isset($this->factories[$type]))
        {
            $this->factories[$type] = match ($type)
            {
                "controller" => new controllerFactory(),
                "model" => new modelFactory(),
                "view" => new viewFactory(),
                "formatter" => new formatterFactory(),
                default => throw new \InvalidArgumentException("Unknown factory type: $type")
            };
        }

        return $this->factories[$type];
    }

    // =============================================================================================
}