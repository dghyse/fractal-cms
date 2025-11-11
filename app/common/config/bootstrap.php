<?php
/**
 * bootstrap.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
use Dotenv\Dotenv;

/**
 * @param string $name environment var
 * @return bool
 */
function getboolenv($name, $default = false) : bool {
    $value = $_ENV[$name] ?? ($_SERVER[$name] ?? $default);
    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

/**
 * @param string $name environment var
 * @param string $default default value
 * @return string
 */
function getstrenv($name, $default = '') : string {
    $value = $_ENV[$name] ?? ($_SERVER[$name] ?? false);
    return $value === false ? $default : $value;
}

/**
 * @param string $name environment var
 * @param int $default default value
 * @return int
 */
function getintenv($name, $default = 0) : int {
    $value = $_ENV[$name] ?? ($_SERVER[$name] ?? false);
    if ($value !== false) {
        $value = filter_var($value, FILTER_VALIDATE_INT);
    }
    return $value === false ? (int)$default : $value;
}

try {
    $dotEnv = Dotenv::createImmutable(dirname(__DIR__, 3));
    $dotEnv->safeLoad();
    $dotEnv->required([
        'YII_ENV',
        'APP_VERSION',
        'DB_DRIVER',
        'DB_DATABASE',
        'DB_USER',
        'DB_HOST',
        'DB_PORT',
        'DB_PASSWORD',
        'DB_SCHEMA',
//        'DB_TABLE_PREFIX',
    ]);
    $dotEnv->required('YII_COOKIE_VALIDATION_KEY')->notEmpty();
    $dotEnv->required('DB_DRIVER')->allowedValues(['mysql', 'pgsql']);
    $dotEnv->required('DB_SCHEMA_CACHE')->isBoolean();
    $dotEnv->required('DB_SCHEMA_CACHE_DURATION')->isInteger();
    if (getboolenv('REDIS_ENABLED')) {
        $dotEnv->required('REDIS_HOST');
        $dotEnv->required('REDIS_PORT')->isInteger();
        $dotEnv->required('REDIS_DATABASE')->isInteger();
    }
} catch (Exception $e) {
    die('Application not configured verif .ENV : '.$e->getMessage());
}

// get wanted debug
$debug = getboolenv('YII_DEBUG');
if ($debug === true) {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// get if app is in maintenance mode
$maintenance = getboolenv('YII_MAINTENANCE');
defined('YII_MAINTENANCE') or define('YII_MAINTENANCE', $maintenance);

$currentEnvironment = getstrenv('YII_ENV');
defined('YII_ENV') or define('YII_ENV', $currentEnvironment);
