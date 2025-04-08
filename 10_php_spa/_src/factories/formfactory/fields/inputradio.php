<?php

class inputRadio extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="radio"' . $this->renderAttributes(["type"]) . '>';
        return $html . PHP_EOL;
    }
}