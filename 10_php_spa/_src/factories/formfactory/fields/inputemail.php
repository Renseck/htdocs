<?php

class inputEmail extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="email"' . $this->renderAttributes(['type']) . '>';
        return $html . PHP_EOL;
    }
}