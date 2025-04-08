<?php

class inputText extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="text"' . $this->renderAttributes(["type"]) . '>';
        return $html . PHP_EOL;
    }
}