<?php

namespace System;

use Helpers\Request;
use Helpers\Response;
use JsonException;
use System\Database\DatabaseInterface;
use System\Database\PDODriver;

class App
{
    /**
     * @param string $uri
     * @param array $routes
     * @return bool|array
     * @throws JsonException
     */
    public function run(string $uri, array $routes): bool|array
    {
        $checkRoute = $this->checkRoute($uri, $routes);

        if (!$checkRoute) {
            return (new Response())->showError(404);
        }

        return $checkRoute;
    }

    /**
     * @param string $uri
     * @param array $routes
     * @return bool|array
     * @throws JsonException
     */
    private function checkRoute(string $uri, array $routes): bool|array
    {
        foreach ($routes as $route) {
            if (($_SERVER['REQUEST_METHOD'] === $route['request_method']) && preg_match($route['route'], $uri)) {
                $request = new Request($_SERVER['REQUEST_URI']);
                $method = $route['method'];
                $controller = "\\App\\Controllers\\${route['controller']}Controller";

                return empty($request->get()) ? (new $controller())->$method() : (new $controller())->$method($request);
            }
        }

        return false;
    }

    /**
     * @param string $databaseName
     * @return DatabaseInterface
     */
    public static function chooseDatabaseConnection(string $databaseName): DatabaseInterface
    {
        return match ($databaseName) {
            'mysql' => PDODriver::getInstance(),
            default => PDODriver::getInstance(),
        };
    }
}