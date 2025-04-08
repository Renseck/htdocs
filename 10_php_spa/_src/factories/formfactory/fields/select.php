<?php

class select extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $selectedValue = $this->getAttribute('value');
        $options = $this->getAttribute('options') ?? [];

        $html = '<select' . $this->renderAttributes(["type", "options", "value"]) . '>';

        foreach ($options as $value => $label) {
            $selected = ($value == $selectedValue) ? ' selected' : '';
            $html .= '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . 
                     htmlspecialchars($label) . '</option>';
        }
        
        $html .= '</select>';
        return $html . PHP_EOL;
    }
}