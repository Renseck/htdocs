<?php

namespace App\views\elements;

use App\interfaces\iPageElement;

/**
 * Elements are taken to mean things like login forms, navigation menus etc
 */
abstract class Element implements iPageElement
{
    private bool $directOutput;
    public string $wrapperClass = "";

    // =============================================================================================
    public function __construct(bool $directOutput = false)
    {
        $this->directOutput = $directOutput;
    }
    /**
     * Add a <div class="..."> wrapper around the elements
     * @param bool $addWrapper Set wrapper true|false
     * 
     * @return string|bool Returns the HTML string with a div wrapper or false and echos directly
     */
    final public function show(bool $addWrapper) : string|bool
    {
        $content = $this->getContent();

        if ($addWrapper)
        {
            if (!empty($this->wrapperClass))
            {
                $content = '<div class="' . $this->wrapperClass . '">' . PHP_EOL
                .$content
                .'</div>' . PHP_EOL;
            }
            
        }

        if ($this->directOutput) {
            echo $content;
            return false;
        }

        return $content;
    }

    // =============================================================================================
    /**
     * Set the wrapper class
     * @param string $className Div class name
     * 
     * @return void
     */
    final public function setWrapperClass(string $className) : void
    {
        $this->wrapperClass = $className;
    }

    // =============================================================================================
    /**
     * @return string HTML string of the element
     */
    abstract public function getContent() : string;

    // =============================================================================================
}