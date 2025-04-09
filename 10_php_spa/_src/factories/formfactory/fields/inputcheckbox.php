<?php

namespace App\factories\formfactory\fields;

use App\factories\formfactory\fields\formElement;

class inputCheckbox extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="checkbox"' . $this->renderAttributes(["type"]) . '>';
        return $html . PHP_EOL;
    }
}