<?php

namespace core;

class Response
{
    /**
     * Send a JSON response.
     *
     * @param array $data The data to be encoded as JSON.
     * @param int $status The HTTP status code (default is 200).
     */
    public static function json(array $data, int $status = 200)
    {
        // Set the HTTP status code
        http_response_code($status);

        // Set the Content-Type header to application/json
        header('Content-Type: application/json');

        // Output the JSON-encoded data
        echo json_encode($data);

        // Terminate the script to ensure no further output
        exit;
    }
    
}