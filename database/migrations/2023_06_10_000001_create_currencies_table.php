<?php

use System\Database\MigrationInterface;
use System\Database\PDODriver;

return new class implements MigrationInterface {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        PDODriver::getInstance()->createTable("currencies", [
            "`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "`currency` varchar(64) NOT NULL",
            "`code` varchar(3) NOT NULL",
            "`bid` float NOT NULL",
            "`ask` float NOT NULL",
            "`effective_date` date NOT NULL",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): bool
    {
        return PDODriver::getInstance()->dropTable("currencies");
    }
};