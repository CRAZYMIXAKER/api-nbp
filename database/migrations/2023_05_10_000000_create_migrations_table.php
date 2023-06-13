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
        PDODriver::getInstance()->createTable("migrations", [
            "`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "`migration` varchar(255) NOT NULL",
            "`batch` int NOT NULL"
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): bool
    {
        return PDODriver::getInstance()->dropTable("migrations");
    }
};