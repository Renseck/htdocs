<?php

class submitButton extends formElement
{
    public function render(): string
    {
        $value = $this->getAttribute('value') ?? 'Submit';
        
        // Add type="submit" if not already set
        if (!isset($this->attributes['type'])) {
            $this->setAttribute('type', 'submit');
        }
        
        $html = '<button' . $this->renderAttributes(['value']) . '>';
        $html .= htmlspecialchars($value);
        $html .= '</button>';
        
        return $html . PHP_EOL;
    }
}