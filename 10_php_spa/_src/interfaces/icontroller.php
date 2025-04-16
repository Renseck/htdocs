<?php

namespace App\interfaces;

interface iController
{
    public function handleRequest() : bool;
}