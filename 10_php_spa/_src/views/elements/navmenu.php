<?php

namespace App\views\elements;

use App\views\elements\Element;

class NavMenu extends Element
{
    public function getContent() : string
    {
        // Is there some nice way of gathering these items dynamically
        $items = [
            'home' => 'HOME',
            'about' => 'ABOUT',
            'contact' => 'CONTACT',
            'login' => "LOGIN"
        ];
        
        $menu = '<ul class="menu">' . PHP_EOL;
        
        foreach ($items as $page => $label) {
            $menu .= '<li><a href="index.php?page=' . $page . '" class="ajax-link">' . $label . '</a></li>' . PHP_EOL;
        }
        
        $menu .= '</ul>';
        return $menu . PHP_EOL;
    }
}