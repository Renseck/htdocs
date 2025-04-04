<?php

namespace factories;

use \factories\baseFactory;

class controllerFactory extends baseFactory
{
    // The models this factory can create
    protected $controllerMap = [];

    // =============================================================================================
    public function __construct()
    {
        $this->controllerMap = [
            "ajax" => \controller\ajaxController::class,
            "api" => \controller\apiController::class,
            "auth" => \controller\authController::class,
            "cart" => \controller\cartController::class,
            "garble" => \controller\garbleController::class,
            "session" => \controller\sessionController::class,
            "post" => \controller\postController::class,
            "get" => \controller\getController::class
        ];
    }

    // =============================================================================================
    public function create(string $type, array $params = [])
    {
        if (!$this->canCreate($type))
        {
            throw new \InvalidArgumentException("Unsupported factory type: $type");
        }

        return new $this->controllerMap[$type](...$params);
    }

    // =============================================================================================
    public function canCreate(string $type)
    {
        return isset($this->controllerMap[$type]);
    }

    // =============================================================================================
}