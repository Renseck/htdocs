<?php

namespace App\interfaces;

interface iPageElement
{
    public function show(bool $addWrapper) : string|bool;
}