<?php

namespace model;

use database\crudOperations;

class productModel
{
    private $crud;

    // =============================================================================================
    public function __construct()
    {
        $this->crud = new crudOperations("products");
    }

    // =============================================================================================
    /**
     * Get all products
     * @param string $orderBy Field to order by
     * @param string $direction Sort direction (ASC or DESC)
     * @return array Array of products
     */
    public function getAllProducts($orderBy = 'name', $direction = 'ASC')
    {
        return $this->crud->read("*", [], "{$orderBy} {$direction}");
    }

    // =============================================================================================
    /**
     * Get product by ID
     * @param int $id Product ID
     * @return array|false Product data or false if not found
     */
    public function getProductById($id)
    {
        return $this->crud->readOne(["product_id" => $id]);
    }

    // =============================================================================================
    /**
     * Search products by name or description
     * @param string $keyword Search term
     * @return array Array of matching products
     */
    public function searchProducts($keyword)
    {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword OR description LIKE :keyword";
        $params = [':keyword' => "%{$keyword}%"];
        $stmt = $this->crud->customQuery($sql, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}