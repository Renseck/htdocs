<?php

namespace App\factories\formfactory\forms;

class registerForm extends Form
{
    public function __construct(array $attributes = [])
    {
        $defaultAttributes = [
            "id" => "register-form",
            "class" => "ajax-form form-register",
            "method" => "post",
            "action" => "index.php?page=register"
        ];

        parent::__construct(array_merge($defaultAttributes, $attributes));
        $this->buildForm();
    }

    // =============================================================================================
    protected function buildForm() : void
    {
        // Add name field
        $this->addElement([
            'type' => 'text',
            'name' => 'name',
            'id' => 'register-name',
            'class' => 'form-control',
            'placeholder' => 'Enter your name',
            'required' => 'required',
            'label' => 'Name'
        ]);

        // Add email field
        $this->addElement([
            'type' => 'email',
            'name' => 'email',
            'id' => 'register-email',
            'class' => 'form-control',
            'placeholder' => 'Enter your email',
            'required' => 'required',
            'label' => 'Email'
        ]);
        
        // Add password field
        $this->addElement([
            'type' => 'password',
            'name' => 'password',
            'id' => 'register-password',
            'class' => 'form-control',
            'placeholder' => 'Enter your password',
            'required' => 'required',
            'label' => 'Password'
        ]);

        // Add password repeat field
        $this->addElement([
            'type' => 'password',
            'name' => 'password_repeat',
            'id' => 'register-password',
            'class' => 'form-control',
            'placeholder' => 'Enter your password',
            'required' => 'required',
            'label' => 'Repeat password'
        ]);
        
        // Add submit button
        $this->addElement([
            'type' => 'submit',
            'value' => 'Register',
            'class' => 'btn btn-primary'
        ]);
    }
}