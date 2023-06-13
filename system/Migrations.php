<?php

namespace System;

use DateTime;
use System\Database\DatabaseInterface;
use System\Database\PDODriver;

class Migrations
{
    private const MIGRATIONS_PATH = 'database/migrations/';

    protected static DatabaseInterface $db;

    /**
     * @param DatabaseInterface $database
     * @return void
     */
    public static function setDb(DatabaseInterface $database): void
    {
        static::$db = $database;
    }

    /**
     * @param bool|string $type
     * @return void
     */
    public function run(bool|string $type = ''): void
    {
        $migrationTableQuery = "SHOW TABLES LIKE 'migrations'";
        $checkMigrationTable = static::$db->query($migrationTableQuery)->fetch();

        if (!$checkMigrationTable) {
            $this->createMigrationTable();
        }

        if ($type === ":make") {
            $this->createMigrationFile();
        }

        if ($type === ":fresh") {
            $clearMigrationsTableQuery = "DELETE FROM migrations WHERE batch > 1";
            static::$db->query($clearMigrationsTableQuery);
            $this->runMigrations();
            echo "\e[33mEvery migration was ran\e[39m" . PHP_EOL;
            exit();
        }

        if ($type === ':rollback') {
            $this->rollbackMigrations();
        }

        if ($type !== ':rollback' && $type !== ':fresh' && $type !== false) {
            $migrationFileName = "{$type}.php";
            $migrationFile = self::MIGRATIONS_PATH . $migrationFileName;

            $sql = "SELECT * FROM migrations WHERE migration=:migration";
            $checkMigrationTable = static::$db->query($sql, ['migration' => $migrationFileName])->fetch();

            if (!$checkMigrationTable && is_readable($migrationFile)) {
                $migration = include __CORE__ . $migrationFile;
                $migration->up();

                $this->addMigration($migrationFileName, $this->getMaxMigrationBatch() + 1);
                exit();
            }

            echo "\e[33mMigration file doesn't issue or you did migrate for this file before!\e[39m" . PHP_EOL;
        }

        if (!$type) {
            $this->runMigrations();
        }
    }

    /**
     * @return void
     */
    protected function createMigrationFile(): void
    {
        $dateTime = new DateTime('now');
        $fileName = __CORE__ . self::MIGRATIONS_PATH . "{$dateTime->format('Y_m_d_Gis')}_{$_SERVER['argv'][2]}.php";
        $fileContent = '<?php

use System\Database\MigrationInterface;
use System\Database\PDODriver;

return new class implements MigrationInterface {
    public function up(): void
    {
        PDODriver::getInstance()->create("test", [
            "`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,",
            "`name` varchar(255) NOT NULL,",
            "`status` int NOT NULL",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        (new PDODriver())->drop("test");
    }
};';

        file_put_contents($fileName, $fileContent);
        echo "\e[33mMigration created!\e[39m" . PHP_EOL;
        exit();
    }

    /**
     * @param $migrationFileName
     * @param $batchNumber
     * @return void
     */
    protected function addMigration($migrationFileName, $batchNumber): void
    {
        echo "\e[33mMigrating:  ${migrationFileName} \e[39m" . PHP_EOL;
        $addOneMigrationQuery = "INSERT INTO migrations (migration, batch) 
                                 VALUES (:migration, :batch)";
        static::$db->query($addOneMigrationQuery, [
            'migration' => $migrationFileName,
            'batch' => $batchNumber
        ]);

        echo "\e[32mMigrated:  $migrationFileName \e[39m" . PHP_EOL;
    }

    /**
     * @return int
     */
    protected function getMaxMigrationBatch(): int
    {
        $batchMaxQuery = "SELECT batch FROM migrations ORDER BY batch DESC LIMIT 1";
        return (static::$db->query($batchMaxQuery)->fetch())['batch'];
    }

    /**
     * @return void
     */
    protected function createMigrationTable(): void
    {
        $createMigrationTable = include __CORE__ . self::MIGRATIONS_PATH . "2023_05_10_000000_create_migrations_table.php";
        $createMigrationTable->up();

        $addMigrationQuery = "INSERT INTO migrations (migration, batch) 
                                  VALUES (:migration, :batch)";
        static::$db->query($addMigrationQuery, [
            'migration' => "2023_05_10_000000_create_migrations_table.php",
            'batch' => 1
        ]);
    }

    /**
     * @return void
     */
    protected function runMigrations(): void
    {
        $migrationFiles = array_diff(scandir(self::MIGRATIONS_PATH), array('..', '.'));

        $migrationsQuery = "SELECT * FROM migrations";
        $migrations = static::$db->query($migrationsQuery)->fetchAll();

        $filteredMigrations = array_diff($migrationFiles, array_filter($migrationFiles, function ($item) use ($migrations) {
            foreach ($migrations as $migration) {
                if ($migration['migration'] === $item) return true;
            }
        }));

        if ($filteredMigrations) {
            $batchMaxNumber = $this->getMaxMigrationBatch() + 1;

            foreach ($filteredMigrations as $migration) {
                $migrationClass = include __CORE__ . self::MIGRATIONS_PATH . $migration;
                $migrationClass->up();
                $this->addMigration($migration, $batchMaxNumber);
            }
        } else {
            echo "\e[33mNothing to migrate!\e[39m" . PHP_EOL;
        }
    }

    /**
     * @return void
     */
    protected function rollbackMigrations(): void
    {
        $maxMigrationBatch = $this->getMaxMigrationBatch();

        $rollbackMigrationQuery = "SELECT * FROM migrations WHERE batch = :batch AND batch > 1";
        $rollbackMigrations = static::$db->query($rollbackMigrationQuery, [
            "batch" => $maxMigrationBatch
        ])->fetchAll();

        if (!empty($rollbackMigrations)) {
            foreach ($rollbackMigrations as $rollbackMigration) {
                $migrationFile = include __CORE__ . self::MIGRATIONS_PATH . $rollbackMigration['migration'];
                $migrationDownResult = $migrationFile->down();

                if ($migrationDownResult) {
                    static::$db->query(
                        'DELETE FROM migrations WHERE migration=:migration AND batch=:batch',
                        [
                            'migration' => $rollbackMigration['migration'],
                            'batch' => $maxMigrationBatch
                        ]
                    );

                    echo "\e[32mRollback: {$rollbackMigration['migration']}\e[39m" . PHP_EOL;
                    continue;
                }

                echo "\e[31mNo Rollback: {$rollbackMigration['migration']}\e[39m" . PHP_EOL;
            }
            exit();
        }
        echo "\e[33mNothing to rollback!\e[39m" . PHP_EOL;
    }
}