<?php

namespace factories;

use \factories\baseFactory;

class modelFactory extends baseFactory
{
    // The models this factory can create
    protected $modelMap = [];

    // =============================================================================================
    public function __construct()
    {
        $this->modelMap = [
            "user" => \model\userModel::class,
            "product" => \model\productModel::class,
            "order" => \model\orderModel::class,
            "rating" => \model\ratingModel::class
        ];
    }

    // =============================================================================================
    public function create(string $type, array $params = [])
    {
        if (!$this->canCreate($type))
        {
            throw new \InvalidArgumentException("Unsupported model type: $type");
        }

        return new $this->modelMap[$type](...$params);
    }

    // =============================================================================================
    public function canCreate(string $type)
    {
        return isset($this->modelMap[$type]);
    }

    // =============================================================================================
}