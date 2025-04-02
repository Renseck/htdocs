<?php

namespace factories;

abstract class baseFactory
{
    /**
     * Create an instance of the target class
     * @param string $type The type of the object to create
     * @param array $params Optional parameters for object creation
     * 
     * @return mixed The created object
     */
    abstract public function create(string $type, array $params = []);

    /**
     * Checks whether the factory can create a specific type of object
     * @param string $type The type of the object to create
     * 
     * @return bool True if it can create, false otherwise
     */
    abstract public function canCreate(string $type);
}