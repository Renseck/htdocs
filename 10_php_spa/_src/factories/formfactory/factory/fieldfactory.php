<?php

namespace App\factories\formfactory\factory;

use App\factories\formfactory\fields\formElement;

class fieldFactory
{
    protected $elements;

    public function __construct()
    {
        // Register default form elements
        $this->setElement('text', \App\factories\formfactory\fields\inputText::class);
        $this->setElement('password', \App\factories\formfactory\fields\inputPassword::class);
        $this->setElement('email', \App\factories\formfactory\fields\inputEmail::class);
        $this->setElement('number', \App\factories\formfactory\fields\inputNumber::class);
        $this->setElement('checkbox', \App\factories\formfactory\fields\inputCheckbox::class);
        $this->setElement('radio', \App\factories\formfactory\fields\inputRadio::class);
        $this->setElement('select', \App\factories\formfactory\fields\select::class);
        $this->setElement('textarea', \App\factories\formfactory\fields\textarea::class);
        $this->setElement('button', \App\factories\formfactory\fields\button::class);
        $this->setElement('submit', \App\factories\formfactory\fields\submitButton::class);
    }

    // =============================================================================================
    /**
     * Create an instance of the target class
     * @param array $params Parameters for object creation
     * 
     * @return formElement The created formElement
     */
    public function create(array $params = []) : formElement
    {
        if (!isset($params["type"]))
        {
            throw new \InvalidArgumentException("Element type must be specified in params array");
        }

        $type = $params['type'];

        if (!$this->canCreate($type))
        {
            throw new \InvalidArgumentException("Unsupported element type: $type");
        }

        return new $this->elements[$type]($params);
    }

    // =============================================================================================
    /**
     * Checks whether the factory can create a specific type of object
     * @param string $type The type of the object to create
     * 
     * @return bool True if it can create, false otherwise
     */
    public function canCreate(string $type) : bool
    {
        return isset($this->elements[$type]);
    }

    // =============================================================================================
    /**
     * Get all registered element types
     * @return array
     */
    public function getElements() : array
    {
        return $this->elements;
    }

    // =============================================================================================
    /**
     * Register a new form element type
     * @param string $type Element type
     * @param string $className Class name to instantiate
     * 
     * @return formFactory
     */
    public function setElement(string $type, string $className) : fieldFactory
    {
        $this->elements[$type] = $className;
        return $this;
    }
    
    // =============================================================================================
}