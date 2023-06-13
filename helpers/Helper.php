<?php

namespace Helpers;

class Helper
{
    /**
     * @param array $array
     * @return string
     */
    public static function getSeparatedArrayBySpace(array $array): string
    {
        return implode(' ', $array);
    }

    /**
     * @param array $array
     * @return string
     */
    public static function getSeparatedArrayByComma(array $array): string
    {
        return implode(', ', $array);
    }

    /**
     * @param array $array
     * @return string
     */
    public static function getSeparatedArrayByColonAndComma(array $array): string
    {
        return self::getSeparatedArrayByComma(array_map(static fn($column) => ":$column", $array));
    }

    /**
     * @param array $array
     * @param string $columnName
     * @return array
     */
    public static function getArrayColumn(array $array, string $columnName): array
    {
        return array_values(array_column($array, $columnName));
    }

    /**
     * @param array $array
     * @param int $count
     * @return int|array|string
     */
    public static function getArrayRand(array $array, int $count): int|array|string
    {
        return array_rand(array_flip($array), $count);
    }

    /**
     * Get links for the navigation bar template
     *
     * @return array
     */
    public static function getNavigationLinks(): array
    {
        return [
            'uri' => $_SERVER['REQUEST_URI']
        ];
    }
}