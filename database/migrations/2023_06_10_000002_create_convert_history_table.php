<?php

use System\Database\MigrationInterface;
use System\Database\PDODriver;

return new class implements MigrationInterface {
    public function up(): void
    {
        PDODriver::getInstance()->createTable("converted_currencies_history", [
            "`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "`requested_value` float NOT NULL",
            "`converted_value` float NOT NULL",
            "`currency_from_id` int NOT NULL",
            "`currency_to_id` int NOT NULL",
            "`converted_at` timestamp default CURRENT_TIMESTAMP",
            "FOREIGN KEY(`currency_from_id`) REFERENCES currencies(`id`) ON DELETE CASCADE",
            "FOREIGN KEY(`currency_to_id`) REFERENCES currencies(`id`) ON DELETE CASCADE",
        ]);
    }

    /**
     * Reverse the migration
     */
    public function down(): bool
    {
        return PDODriver::getInstance()->dropTable("converted_currencies_history");
    }
};