<?php

class textarea extends formElement
{
    // =============================================================================================
    public function render() : string
    {
        $value = $this->getAttribute('value') ?? '';

        $html = '<textarea' . $this->renderAttributes(['type', 'value']) . '>';
        $html .= htmlspecialchars($value);
        $html .= '</textrea>';

        return $html . PHP_EOL;
    }
}