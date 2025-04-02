<?php

namespace factories;

class factoryManager
{
    protected array $factories = [];
    
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
    public function registerFactory($factory) : void
    {
        $this->factories[] = $factory;
    }

    // =============================================================================================
    public function create(string $type, array $params = [])
    {
        foreach ($this->factories as $factory)
        {
            if ($factory->canCreate($type))
            {
                return $factory->create($type, $params);
            }
        }

        throw new \InvalidArgumentException("No factory found for type: $type");
    }

}