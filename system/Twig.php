<?php

namespace System;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class Twig
{
    private const VIEW_PATH = __CORE__ . 'app/Views/';

    /**
     * @param string $path
     * @param array $values
     * @return string
     */
    public function makeTemplate(string $path, array $values = []): string
    {
        static $twig;

        if ($twig === null) {
            $loader = new FilesystemLoader(self::VIEW_PATH);
            $twig = new Environment($loader, [
                'debug' => true,
                'cache' => __CORE__ . 'cache/',
                'auto_reload' => true,
                'autoescape' => false,
                'strict_variables' => true
            ]);
            $twig->addExtension(new DebugExtension());
        }

        try {
            return $twig->render("{$path}.twig", $values);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            die($e);
        }
    }
}