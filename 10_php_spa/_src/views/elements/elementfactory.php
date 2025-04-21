<?php

namespace App\views\elements;

/**
 * Factory for creating page elements
 * Provides a central point for instantiating different element types
 */
class ElementFactory
{
    /**
     * Create a page element
     * @param string $elementType Type of element to create
     * @param bool $directOutput Whether to directly output HTML or return it
     * @param string $wrapperClass CSS class to add to wrapper div
     * @param array $options Additional options for specific element types
     * 
     * @return Element|null The created element
     */
    public function createElement(string $elementType, bool $directOutput = false, string $wrapperClass = "", array $options = []) : ?Element
    {
        $element = null;
        
        switch(strtolower($elementType)) {
            case 'header':
            case 'defaultheader':
                $element = new DefaultHeader($directOutput);
                break;
                
            case 'navmenu':
            case 'navigation':
            case 'nav':
                $element = new NavMenu($directOutput);
                break;
                
            case '404':
            case 'notfound':
            case 'pagenotfound':
                $element = new PageNotFound($directOutput);
                break;

            case "contactform":
                $element = new ContactForm($directOutput);
                break;

            case "loginform":
                $element = new LoginForm($directOutput);
                break;

            case "registerform":
                $element = new RegistrationForm($directOutput);
                break;

            case "homemsg":
                $element = new HomeMessage($directOutput);
                break;

                
            default:
                // Unknown element type
                return null;
        }
        
        // Set wrapper class if provided
        if (!empty($wrapperClass) && $element !== null) {
            $element->setWrapperClass($wrapperClass);
        }
        
        // Apply any custom configuration from options
        $this->configureElement($element, $options);
        
        return $element;
    }
    
    /**
     * Create multiple elements at once
     * @param array $elements Array of element configurations
     * 
     * @return array Array of created elements
     */
    public function createElements(array $elements) : array
    {
        $createdElements = [];
        
        foreach ($elements as $elementConfig) {
            $type = $elementConfig['type'] ?? '';
            $directOutput = $elementConfig['directOutput'] ?? false;
            $wrapperClass = $elementConfig['wrapperClass'] ?? '';
            $options = $elementConfig['options'] ?? [];
            
            $element = $this->createElement($type, $directOutput, $wrapperClass, $options);
            if ($element !== null) {
                $createdElements[] = $element;
            }
        }
        
        return $createdElements;
    }
    
    /**
     * Configure an element with custom options
     * @param Element $element The element to configure
     * @param array $options Options for configuration
     * 
     * @return void
     */
    protected function configureElement(?Element $element, array $options) : void
    {
        // Add custom configuration logic for specific element types
        if ($element === null) {
            return;
        }
        
        // Example: if this is a NavMenu and options contains 'items'
        if ($element instanceof NavMenu && isset($options['items'])) {
            // Future implementation for configuring NavMenu items
        }
    }
}