<?php

namespace App\views\elements;

use App\views\elements\Element;

class PageNotFound extends ELement
{
    public function getContent() : string
    {
        return '<h1>Page 404</h1>' . PHP_EOL
              .'<p>Page not found. Return home <a href="index.php?page=home" class="ajax-link">here</a>.</p>' . PHP_EOL;
    }
}