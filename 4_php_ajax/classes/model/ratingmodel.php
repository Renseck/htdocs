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
     * @param int $rating The rating of the product (1-5)
     * @return int|bool
     */
    public function rateProduct(int $productId, int $userId, int $rating) : int|bool
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
            // Prevent user from rating more than once
            return false;
            
            // Use this code if you DO want the user to update their rating
            // return $this->crud->update(
            //     ["rating" => $rating],
            //     ["product_id" => $productId, "user_id" => $userId]
            // );
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
     * @return int|null
     */
    public function getUserRating(int $productId, int $userId) : int|null
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
     * @param  int $productId The product ID
     * @return array
     */
    public function getAverageRating(int $productId) : array
    {
        // Use a custom query to calculate the average
        $sql = "SELECT AVG(rating) as average,
                COUNT(*) as count
                FROM product_ratings
                WHERE product_id = :product_id";

        $params = [":product_id" => $productId];
        $stmt = $this->crud->customQuery($sql, $params);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

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
     * @param int $productId The product ID
     * @return array
     */
    public function getProductRatings(int $productId) : array
    {
        return $this->crud->read("*", ["product_id" => $productId], "created_at DESC");
    }

    // =============================================================================================
    /**
     * Get all ratings made by a user
     * @param int $userId The user ID
     * @return array
     */
    public function getUserRatings(int $userId) : array
    {
        // Custom query for all orders made by a user
        $sql = "SELECT pr.*, p.name  as product_name
                FROM product_ratings pr
                INNER JOIN products p on pr.product_id = p.product_id
                WHERE pr.user_id = :user_id
                ORDER BY pr.created_at DESC";

        $params = [":user_id" => $userId];
        $result = $this->crud->customQuery($sql, $params);
        return $result ? $result : [];
    }

    // =============================================================================================
    /**
     * Delete a rating from the record
     * @param int $productId The product ID
     * @param int $userId The user ID
     * @return int|bool
     */
    public function deleteRating(int $productId, int $userId) : int|bool
    {
        return $this->crud->delete(["product_id" => $productId, "user_id" => $userId]);
    }
    
    // =============================================================================================
}