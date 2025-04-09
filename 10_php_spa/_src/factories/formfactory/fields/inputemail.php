<?php

namespace App\factories\formfactory\fields;

use App\factories\formfactory\fields\formElement;

class inputEmail extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="email"' . $this->renderAttributes(['type']) . '>';
        return $html . PHP_EOL;
    }
}