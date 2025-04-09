<?php

namespace App\factories\formfactory\factory;

use App\factories\formfactory\forms\Form;

class formFactory
{
    const TYPE_LOGIN = "login";
    const TYPE_CONTACT = "contact";
    const TYPE_REGISTER = "register";
    const TYPE_CUSTOM = "custom";

    protected $formTypes = [
        self::TYPE_LOGIN => \App\factories\formfactory\forms\loginForm::class,
        self::TYPE_CONTACT => \App\factories\formfactory\forms\contactForm::class,
        self::TYPE_REGISTER => \App\factories\formfactory\forms\registerForm::class,
        self::TYPE_CUSTOM => \App\factories\formfactory\forms\Form::class
    ];

    // =============================================================================================
    public function create(string $type = self::TYPE_CUSTOM, array $attributes = []) : Form
    {
        if (!$this->canCreate($type))
        {
            throw new \InvalidArgumentException("Unsupported form type: $type");
        }

        return new $this->formTypes[$type]($attributes);
    }
    
    // =============================================================================================
    public function canCreate(string $type) : bool
    {
        return isset($this->formTypes[$type]);
    }

    // =============================================================================================
}