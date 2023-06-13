<?php

namespace Helpers;

use JsonException;

class Request
{
    private array $data = [];
    private mixed $method;
    private mixed $uri;

    /**
     * @throws JsonException
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->setParameters();
    }

    /**
     * Get a value of request by name
     *
     * @param string $name Name of requests value.
     * @return string|array Requests value.
     */
    public function get(string $name = ''): string|array
    {
        return $name === '' ? $this->data : $this->data[$name];
    }

    /**
     * Get all values fo request
     *
     * @return array
     */
    public function getAll(): array
    {
        return [
            "data" => $this->data,
            "method" => $this->method,
            "uri" => $this->uri,
        ];
    }

    /**
     * @return void
     * @throws JsonException
     */
    private function setParameters(): void
    {
        $jsonData = file_get_contents('php://input');
        $params = array_filter(explode('/', $this->uri), static fn($item) => is_numeric($item) ?: '');

        if ($params) {
            $this->data = ["id" => (int)reset($params)];
        }

        if ($_GET) {
            $this->data = ["get" => $_GET];
        }

        if ($_POST) {
            $this->data = ["post" => $_POST];
        }

        if ($_FILES) {
            $this->data = ["files" => $_FILES];
        }

        if ($jsonData) {
            json_decode($jsonData, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->data = ['json' => json_decode($jsonData, false, 512, JSON_THROW_ON_ERROR)];
            } else {
                parse_str($jsonData, $this->data['json']);
            }
        }
    }
}