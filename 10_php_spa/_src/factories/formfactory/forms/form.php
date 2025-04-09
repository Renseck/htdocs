<?php

namespace App\factories\formfactory\forms;

use App\factories\formfactory\factory\fieldFactory;

class Form
{

    private $fieldFactory;
    private $attributes = [];
    private $elements = [];

    // =============================================================================================
    public function __construct(array $attributes = [])
    {
        $this->fieldFactory = new fieldFactory();
        
        // Set default form attributes
        $this->attributes = array_merge([
            'method' => 'post',
            'action' => ''
        ], $attributes);
    }

    // =============================================================================================
    public function setAttribute(string $name, string $value) : Form
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    // =============================================================================================
    public function getAttribute($name) : string|null
    {
        return $this->attributes[$name] ?? null;
    }

    // =============================================================================================
    public function addElement(array $params) : Form
    {
        $labelText = $this->generateLabelText($params);
        $elementId = $params["id"] ?? ($params["name"] ?? "");

        // Create a div wrapper for the label and field
        $wrapper = [
            "type" => "wrapper",
            "label" => $labelText,
            "for" => $elementId,
            "element" => $this->fieldFactory->create($params)
        ];
        
        $this->elements[] = $wrapper;
        return $this;
    }

    // =============================================================================================
    private function generateLabelText(array $params) : string
    {
        // If label is explicitly provided, use it
        if (isset($params['label'])) {
            return $params['label'];
        }
        
        // Otherwise generate from name
        if (isset($params['name'])) {
            // Convert camelCase or snake_case to words with spaces
            $name = $params['name'];
            $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name); // camelCase to spaces
            $name = str_replace('_', ' ', $name); // snake_case to spaces
            return ucwords($name); // Capitalize first letter of each word
        }
        
        // Fall back to empty if no name or label
        return '';
    }

    // =============================================================================================
    public function render() : string
    {
        // Start with opening form tag including all attributes
        $html = '<form';

        // Add all attributes inside the opening tag
        foreach ($this->attributes as $name => $value)
        {
            $html .= ' ' . htmlspecialchars($name) . '="' . htmlspecialchars($value) . '"';
        }

        // Close the opening tag
        $html .= '>' . PHP_EOL;

        // Render form elements
        foreach ($this->elements as $wrapper)
        {
            if ($wrapper["type"] === "wrapper")
            {   
                $html .= '<div class="form-group">' . PHP_EOL;

                if (!empty($wrapper["label"]) && !empty($wrapper["for"]))
                {
                    $html .= ' <label for="' . htmlspecialchars($wrapper["for"]) . '">'
                          . htmlspecialchars($wrapper["label"]) . '</label>' . PHP_EOL;
                }
            }

            $html .= ' ' . $wrapper["element"]->render() . PHP_EOL;
            $html .= '</div>' . PHP_EOL;
        }

        $html .= "</form>" . PHP_EOL;

        return $html;
    }

}