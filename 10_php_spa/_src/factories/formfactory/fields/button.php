<?php

class button extends formElement
{
    public function render(): string
    {
        $value = $this->getAttribute('value') ?? '';
        
        $html = '<button' . $this->renderAttributes(['value']) . '>';
        $html .= htmlspecialchars($value);
        $html .= '</button>';
        
        return $html . PHP_EOL;
    }
}