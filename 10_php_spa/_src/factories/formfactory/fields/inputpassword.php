<?php

namespace App\factories\formfactory\fields;

use App\factories\formfactory\fields\formElement;

class inputPassword extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="password"' . $this->renderAttributes(['type']) . '>';
        return $html . PHP_EOL;
    }
}