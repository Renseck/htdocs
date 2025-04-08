<?php

class fieldFactory
{
    protected $elements;

    public function __construct()
    {
        // Register default form elements
        $this->setElement('text', 'inputText');
        $this->setElement('password', 'inputPassword');
        $this->setElement('email', 'inputEmail');
        $this->setElement('number', 'inputNumber');
        $this->setElement('checkbox', 'inputCheckbox');
        $this->setElement('radio', 'inputRadio');
        $this->setElement('select', 'select');
        $this->setElement('textarea', 'textarea');
        $this->setElement('button', 'button');
        $this->setElement('submit', 'submitButton');
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