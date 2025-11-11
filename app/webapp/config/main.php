<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

use yii\redis\Session as RedisSession;
use yii\web\DbSession as DbSession;
use yii\web\JsonParser;
use yii\web\ErrorHandler;
use yii\web\Session;

$config = require dirname(dirname(__DIR__)).'/common/config/common.php';

$config['basePath'] = dirname(__DIR__);
$config['id'] = 'dghyse/site-fractal-cms';
$config['name'] = 'Fractal CMS';

$config['controllerNamespace'] = 'app\webapp\controllers';

$config['components']['request'] = [
    'cookieValidationKey' => getstrenv('YII_COOKIE_VALIDATION_KEY'),
    'parsers' => [
        'application/json' => JsonParser::class,
    ],
    'csrfCookie' => [
        'httpOnly' => true,
        'secure' => true
    ]
];


$config['components']['session'] = [
    'class' => Session::class,
    'useCookies' => true,
    'cookieParams' => [
        'httponly' => true,
        'secure' => true
    ]
];

$sessionDbEnabled = getboolenv('YII_SESSION_DB_ENABLED');
$sessionRedisEnabled = getboolenv('YII_SESSION_REDIS_ENABLED');
$redisEnabled = getboolenv('REDIS_ENABLED');
if ($redisEnabled && $sessionRedisEnabled) {
    $config['components']['session']['class'] = RedisSession::class;
    $config['components']['session']['redis'] = 'redis';
} elseif ($sessionDbEnabled) {
    $config['components']['session']['class'] = DbSession::class;
    $config['components']['session']['db'] = 'db';
}
$isDebug = false;
if (defined('YII_ENV') && YII_ENV !== 'dev') {
    $config['components']['errorHandler'] = [
        'class' => ErrorHandler::class,
        'errorAction' => 'technical/error'
    ];
}

if (defined('YII_ENV') && YII_ENV === 'dev') {
    $isDebug = true;
    $yiiGii = class_exists(yii\gii\Module::class);
    if ($yiiGii && defined('YII_DEBUG') && YII_DEBUG == true) {
        $config['modules']['gii'] = [
            'class' => yii\gii\Module::class,
            'allowedIPs' => ['*']
        ];
        $config['bootstrap'][] = 'gii';
    }
    $yiiDebug = class_exists(yii\debug\Module::class);
    if ($yiiDebug && defined('YII_DEBUG') && YII_DEBUG == true) {
        $config['modules']['debug'] = [
            'class' => yii\debug\Module::class,
            'allowedIPs' => ['*']
        ];
        $config['bootstrap'][] = 'debug';
    }
}


$config['components']['assetManager'] = [
    'linkAssets' => false,
    'hashCallback' => function ($path) {
        return hash('md4', $path);
    }
];
if( $isDebug === true) {
    $config['components']['assetManager']['linkAssets'] = true;
}

$config['defaultRoute'] = '/site/index';
if (YII_MAINTENANCE === true) {
    $allowedIp = preg_split('/\s*,\s*/', getstrenv('YII_MAINTENANCE_ALLOWED_IPS'));
    if (in_array($_SERVER['REMOTE_ADDR'], $allowedIp) === false) {
        $config['catchAll'] = ['technical/maintenance'];
    }
}

$config['components']['urlManager'] = [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => false, // should be true in real life
    'showScriptName' => false,
    'rules' => [
        [
            'pattern' => '',
            'route' => $config['defaultRoute'],
        ],
        [
            'pattern' => 'me-contacter',
            'route' => 'contact/post-form',
        ],
        [
            'pattern' => 'sitemap.xml',
            'route' => 'site/sitemap',
        ],
        [
            'pattern' => 'robots.txt',
            'route' => 'site/robot-txt',
        ],
    ],
];
return $config;
