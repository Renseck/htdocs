<?php

namespace formatters;

use \interfaces\formatterInterface;

class jsonFormatter implements formatterInterface
{

    // =============================================================================================
    public function formatResponse(array $data): string
    {
        return json_encode([
            "success" => true,
            "data" => $data
            ]);
    }

    // =============================================================================================
    public function formatError(string $message): string
    {
        return json_encode([
            "success" => false,
            "message" => $message
            ]);
    }

    // =============================================================================================
    public function getContentType(): string
    {
        return "application/json";
    }
}