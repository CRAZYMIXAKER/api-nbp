<?php
const __CORE__ = __DIR__ . '/../';
require_once __CORE__ . 'vendor/autoload.php';

use App\Models\Model;
use Commands\DB;
use System\App;
use System\DotEnv;
use System\Twig;

(new DotEnv(__CORE__ . '.env'))->load();

// Set the database connection in the main model to work with the database in all models
Model::setDb(App::chooseDatabaseConnection(getenv('DB_CONNECTION')));

$routes = require __CORE__ . 'system/routes.php';
$result = (new App())->run($_SERVER['REQUEST_URI'], $routes);

echo (new Twig())->makeTemplate($result['path'], $result);