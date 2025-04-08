<?php

class inputPassword extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $html = '<input type="password"' . $this->renderAttributes(['type']) . '>';
        return $html . PHP_EOL;
    }
}