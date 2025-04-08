<?php

class formFactory
{
    const TYPE_LOGIN = "login";
    const TYPE_CONTACT = "contact";
    const TYPE_REGISTER = "register";
    const TYPE_CUSTOM = "custom";

    // =============================================================================================
    public function create(string $type = self::TYPE_CUSTOM, array $attributes = []) : Form
    {
        switch($type)
        {
            case self::TYPE_LOGIN:
                return $this->createLoginForm($attributes);
                break;

            case self::TYPE_CONTACT:
                return $this->createContactForm($attributes);
                break;

            case self::TYPE_REGISTER:
                return $this->createRegisterForm($attributes);
                break;

            case self::TYPE_CUSTOM:
            default:
                return $this->createForm($attributes);
        }
    }
    
    // =============================================================================================
    public function createForm(array $attributes = [])
    {
        $form = new Form();

        foreach ($attributes as $name => $value)
        {
            $form->setAttribute($name, $value);
        }

        return $form;
    }
    
    // =============================================================================================
    protected function createLoginForm($attributes) : Form
    {
        $defaultAttributes = [
            "id" => "login-form",
            "class" => "form-login",
            "method" => "post",
            "action" => "index.php"
        ];

        $formAttributes = array_merge($defaultAttributes, $attributes);
        $form = $this->createForm($formAttributes);

        // Add email field
        $form->addElement([
            'type' => 'email',
            'name' => 'email',
            'id' => 'login-email',
            'class' => 'form-control',
            'placeholder' => 'Enter your email',
            'required' => 'required',
            'label' => 'Email'
        ]);
        
        // Add password field
        $form->addElement([
            'type' => 'password',
            'name' => 'password',
            'id' => 'login-password',
            'class' => 'form-control',
            'placeholder' => 'Enter your password',
            'required' => 'required',
            'label' => 'Password'
        ]);
        
        // Add submit button
        $form->addElement([
            'type' => 'submit',
            'value' => 'Login',
            'class' => 'btn btn-primary'
        ]);
        
        return $form;
    }

    // =============================================================================================
    protected function createRegisterForm($attributes)
    {
        $defaultAttributes = [
            "id" => "register-form",
            "class" => "form-register",
            "method" => "post",
            "action" => "index.php"
        ];

        $formAttributes = array_merge($defaultAttributes, $attributes);
        $form = $this->createForm($formAttributes);

        // Add name field
        $form->addElement([
            'type' => 'text',
            'name' => 'name',
            'id' => 'register-name',
            'class' => 'form-control',
            'placeholder' => 'Enter your name',
            'required' => 'required',
            'label' => 'Name'
        ]);

        // Add email field
        $form->addElement([
            'type' => 'email',
            'name' => 'email',
            'id' => 'register-email',
            'class' => 'form-control',
            'placeholder' => 'Enter your email',
            'required' => 'required',
            'label' => 'Email'
        ]);
        
        // Add password field
        $form->addElement([
            'type' => 'password',
            'name' => 'password',
            'id' => 'register-password',
            'class' => 'form-control',
            'placeholder' => 'Enter your password',
            'required' => 'required',
            'label' => 'Password'
        ]);

        // Add password repeat field
        $form->addElement([
            'type' => 'password',
            'name' => 'password_repeat',
            'id' => 'register-password',
            'class' => 'form-control',
            'placeholder' => 'Enter your password',
            'required' => 'required',
            'label' => 'Repeat password'
        ]);
        
        // Add submit button
        $form->addElement([
            'type' => 'submit',
            'value' => 'Register',
            'class' => 'btn btn-primary'
        ]);
        
        return $form;
    }

    // =============================================================================================
    protected function createContactForm($attributes)
    {
        $defaultAttributes = [
            "id" => "register-form",
            "class" => "form-register",
            "method" => "post",
            "action" => "index.php"
        ];

        $formAttributes = array_merge($defaultAttributes, $attributes);
        $form = $this->createForm($formAttributes);

        // Add name field
        $form->addElement([
            'type' => 'text',
            'name' => 'name',
            'id' => 'contact-name',
            'class' => 'form-control',
            'placeholder' => 'Enter your name',
            'required' => 'required',
            'label' => 'Name'
        ]);

        // Add email field
        $form->addElement([
            'type' => 'email',
            'name' => 'email',
            'id' => 'contact-email',
            'class' => 'form-control',
            'placeholder' => 'Enter your email',
            'required' => 'required',
            'label' => 'Email'
        ]);

        // Add message textarea
        $form->addElement([
            'type' => 'textarea',
            'name' => 'message',
            'id' => 'contact-message',
            'class' => 'form-control',
            'placeholder' => 'Enter your message',
            'required' => 'required',
            'rows' => '5',
            'label' => 'Message'
        ]);
        
        // Add submit button
        $form->addElement([
            'type' => 'submit',
            'value' => 'Send message',
            'class' => 'btn btn-primary'
        ]);
        
        return $form;
    }
}