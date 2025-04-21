<?php

namespace App\views\elements;

use App\views\elements\Element;

class HomeMessage extends Element
{
    public function getContent() : string
    {
        return '<h1>Home</h1>
			<p>
			Welcome to my first website, built on my first day of work at Educom.
			This serves as my first assignment. Lorem ipsum etc.
			</p>
			<br>' . PHP_EOL;
    }
}