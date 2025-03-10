<?php
namespace config;

class pageConfig 
{
    // =====================================================================
    public static function getPages(): array
    {
        return [
            "home" => "view\\homePage",
            "about" => "view\\aboutPage",
            "contact" => "view\\contactPage",
            "webshop" => "view\\webshopPage",
            "login" => "view\\loginPage",
            "register" => "view\\registerPage",
            "logout" => "view\\logoutPage",
            "cart" => "view\\cartPage"
        ];
    }
    // =====================================================================
}