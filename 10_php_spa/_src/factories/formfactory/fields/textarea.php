<?php

namespace App\factories\formfactory\fields;

use App\factories\formfactory\fields\formElement;

class textarea extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $value = $this->getAttribute('value') ?? '';

        $html = '<textarea' . $this->renderAttributes(['type', 'value']) . '>';
        $html .= htmlspecialchars($value);
        $html .= '</textarea>';

        return $html . PHP_EOL;
    }
}