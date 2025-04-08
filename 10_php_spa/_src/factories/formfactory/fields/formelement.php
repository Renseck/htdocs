<?php

abstract class formElement
{
    protected $attributes = [];

    // =============================================================================================
    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) 
        {
            $this->setAttribute($key, $value);
        }
    }

    // =============================================================================================
    public function setAttribute($name, $value) : self
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    // =============================================================================================
    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    // =============================================================================================
    protected function renderAttributes(array $exclude = [])
    {
        $html = "";

        foreach ($this->attributes as $name => $value)
        {
            if(in_array($name, $exclude)) continue;
            $html .= ' ' . htmlspecialchars($name) . '="' . htmlspecialchars($value) . '"';
        }

        return $html;
    }

    // =============================================================================================
    abstract public function render() : string;
}