<?php

namespace App\factories\formfactory\fields;

use App\factories\formfactory\fields\formElement;

class inputNumber extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="number"' . $this->renderAttributes(["type"]) . '>';
        return $html . PHP_EOL;
    }
}