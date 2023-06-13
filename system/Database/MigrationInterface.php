<?php

namespace System\Database;

interface MigrationInterface
{
    /**
     * @return void
     */
    public function up(): void;

    /**
     * @return bool
     */
    public function down(): bool;
}