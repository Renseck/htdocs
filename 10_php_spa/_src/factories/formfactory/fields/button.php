<?php

namespace App\factories\formfactory\fields;

use App\factories\formfactory\fields\formElement;

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