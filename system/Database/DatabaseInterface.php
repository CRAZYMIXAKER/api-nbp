<?php

namespace System\Database;

interface DatabaseInterface
{
    /**
     * @return mixed
     */
    public function connect(): mixed;

    /**
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function query(string $query, array $params = []): mixed;

    /**
     * @param $query
     * @return bool
     */
    public function checkError($query): bool;
}