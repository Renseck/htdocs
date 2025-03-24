<?php

namespace model;

use database\crudOperations;

class ratingModel
{
    private $crud;

    // =============================================================================================
    public function __construct()
    {
        $this->crud = new crudOperations("product_ratings");
    }

    // =============================================================================================
    /**
     * Rate a product
     * @param int $productId The product ID
     * @param int $userId The user ID
     * @param int $rating The rating of the product
     * @return int|bool
     */
    public function rateProduct(int $productId, int $userId, int $rating)
    {
        // Validate rating value
        if ($rating < 1 || $rating > 5)
        {
            return false;
        }

        // Check if user has already rated the product
        $existingRating = $this->getUserRating($productId, $userId);

        if (!empty($existingRating))
        {
            // Update existing rating
            return $this->crud->update(
                ["rating" => $rating],
                ["product_id" => $productId, "user_id" => $userId]
            );
        }
        else 
        {
            // Insert new rating
            $data = [
                "product_id" => $productId,
                "user_id" => $userId,
                "rating" => $rating
            ];

            return $this->crud->create($data) ? true : false;
        }
    }

    // =============================================================================================
    /**
     * Get a user's rating for a product
     * @param int $productId The product ID
     * @param int $userId The user ID
     * @return array|null
     */
    public function getUserRating(int $productId, int $userId)
    {
        $result = $this->crud->read("rating", [
            "product_id" => $productId,
            "user_id" => $userId
        ]);
        
        return !empty($result) ? (int)$result[0]['rating'] : null;
    }

    // =============================================================================================
    /**
     * Get the average rating for a product
     * @param mixed $productId The product ID
     * @return array
     */
    public function getAverageRating($productId)
    {
        // Use a custom query to calculate the average
        $sql = "SELECT AVG(rating) as average,
                COUNT(*) as count
                FROM product_ratings
                WHERE product_id = :product_id";

        $params = [":product_id" => $productId];
        $result = $this->crud->customQuery($sql, $params);

        if ($result && isset($result[0]))
        {
            return [
                "average" => $result[0]["average"] ? round($result[0]["average"], 1) : 0,
                "count" => $result[0]["count"]
            ];
        }

        return ["average" => 0, "count" => 0];
    }

    // =============================================================================================    
    /**
     * Get all ratings for a product
     * @param mixed $productId The product ID
     * @return array
     */
    public function getProductRatings($productId)
    {
        return $this->crud->read("*", ["product_id" => $productId], "created_at DESC");
    }

    // =============================================================================================
    /**
     * Get all ratings made by a user
     * @param mixed $userId The user ID
     * @return array
     */
    public function getUSerRatings($userId)
    {
        // Custom query for all orders made by a user
        $sql = "SELECT pr.*, p.name  as product_name
                FROM product_ratings pr
                INNER JOIN products p on pr.product_id = p.product_id
                WHERE pr.user_id = :user_id
                ORDER BY pr.created_at DESC";

        $params = [":user_id" => $userId];
        return $this->crud->customQuery($sql, $params);
    }

    // =============================================================================================
    /**
     * Delete a rating from the record
     * @param mixed $productId The product ID
     * @param mixed $userId The user ID
     * @return int|bool
     */
    public function deleteRating($productId, $userId)
    {
        return $this->crud->delete(["product_id" => $productId, "user_id" => $userId]);
    }
    
    // =============================================================================================
}