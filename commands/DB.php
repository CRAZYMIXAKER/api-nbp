<?php

namespace Commands;

use DateTime;
use System\Migrations;

system("clear");

/*
 * The class for working with the database with console commands
 */

class DB
{
    private array $params = [
        'h::' => 'help::',
        'm::' => 'migrate::',
    ];

    /**
     * @return void
     */
    public function run(): void
    {
        $time = new DateTime('now');
        echo "\e[36m{$time->format('G:i:s')} \e[39m\n";

        $options = getopt(implode('', array_keys($this->params)), $this->params);

        if (isset($options['migrate']) || isset($options['m'])) {
            (new Migrations())->run($options['migrate'] ?? $options['m']);
        }

        if (empty($options)) {
            echo "\e[31mError, Command Not Found\e[39m\n";
            $this->help();
        }

        if (isset($options['help']) || isset($options['h'])) {
            $this->help();
        }

        echo "\n";
    }

    /**
     * @return void
     */
    private function help(): void
    {
        echo "\e[32m==============================================" . PHP_EOL;
        echo "Program Help" . PHP_EOL;
        echo "==============================================" . PHP_EOL;
        echo "usage: php public/index.php [-h|--help]" . PHP_EOL;
        echo "[-m|--migrate|-m={fileName}|-m:rollback|-m:fresh|-m:make {timestamp}_create_user_table] " . PHP_EOL;
        echo "Options:" . PHP_EOL;
        echo " -h               --help                   Directory call" . PHP_EOL;
        echo " -m               --migrate                Run the database migrations" . PHP_EOL;
        echo " -m:fresh         --migrate:fresh          Run migrations from scratch" . PHP_EOL;
        echo " -m:rollback      --migrate:rollback       Rollback last migration" . PHP_EOL;
        echo " -m={fileName}    --migrate={fileName}     Run the database migration" . PHP_EOL;
        echo " -m:make {timestamp}_create_user_table     Make default migration file" . PHP_EOL;
        echo "Example: php public/index.php -m\e[39m" . PHP_EOL;
    }
}