<?php

namespace Helpers;

use JsonException;

class Response
{
    /**
     * @param int $codeError
     * @return array
     */
    public function showError(int $codeError): array
    {
        http_response_code($codeError);
        $navigationLinks = Helper::getNavigationLinks();

        return [
            'path' => 'error',
            'codeError' => $codeError,
            'links' => $navigationLinks
        ];
    }

    /**
     * @param $route
     * @return void
     */
    public function redirect($route): void
    {
        header("Location: $route");
        return;
    }

    /**
     * @param $result
     * @return void
     * @throws JsonException
     */
    public function json($result): void
    {
        echo json_encode($result, JSON_THROW_ON_ERROR);
        header('Content-Type: application/json');
        return;
    }
}