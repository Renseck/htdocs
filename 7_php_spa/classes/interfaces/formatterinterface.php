<?php

namespace interfaces;

interface formatterInterface
{
    /**
     * Format data to the appropriate content type
     * @param array $data The data to format
     * 
     * @return string The formatted response
     */
    public function formatResponse(array $data) : string;

    /**
     * Format an error response
     * @param string $message The error message
     * 
     * @return string The formatted error message
     */
    public function formatError(string $message) : string;

    /**
     * Get the content header value
     * @return string The content type
     */
    public function getContentType() : string;
}