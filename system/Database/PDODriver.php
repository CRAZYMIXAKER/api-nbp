<?php

namespace System\Database;

use Helpers\Helper;
use PDO;
use PDOException;
use PDOStatement;

class PDODriver implements DatabaseInterface
{
    private static array|PDODriver $instances = [];
    private string $db_host;
    private string $db_name;
    private string $db_login;
    private string $db_pass;
    private static $db;
    private array $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    protected function __construct()
    {
        $this->db_host = getenv('DB_HOST');
        $this->db_name = getenv('DB_DATABASE');
        $this->db_login = getenv('DB_USERNAME');
        $this->db_pass = getenv('DB_PASSWORD');
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \RuntimeException("Cannot unserialize a singleton.");
    }

    /**
     * @return PDODriver
     */
    public static function getInstance(): PDODriver
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    /**
     * @return PDO
     */
    public function connect(): PDO
    {
        if (self::$db === null) {
            self::$db = new PDO('mysql:host=' . $this->db_host . ';dbname=' .
                $this->db_name, $this->db_login, $this->db_pass, $this->options);
            self::$db->exec('SET NAMES UTF8');
        }
        return self::$db;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return PDOStatement|bool
     */
    public function query(string $sql, array $params = []): PDOStatement|bool
    {
        self::$db = $this->connect();
        self::$db->beginTransaction();
        $query = self::$db->prepare($sql);
        $query->execute($params);

        if ($this->checkError($query)) {
            return false;
        }

        self::$db->commit();
        return $query;
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @return bool
     */
    public function createTable(string $tableName, array $columns): bool
    {
        $createTableQuery = sprintf(
            "CREATE TABLE IF NOT EXISTS %s (%s);",
            $tableName,
            Helper::getSeparatedArrayByComma($columns)
        );

        try {
            $this->connect()->exec($createTableQuery);
        } catch (PDOException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $tableName
     * @return bool
     */
    public function dropTable(string $tableName): bool
    {
        $dropTableQuery = "DROP TABLE IF EXISTS `$tableName`;";

        try {
            $this->connect()->exec($dropTableQuery);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @param $query
     * @return bool
     */
    public function checkError($query): bool
    {
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            self::$db->rollBack();
            echo "\e[31m{$errInfo[2]}\e[39m";
            return true;
        }

        return false;
    }
}