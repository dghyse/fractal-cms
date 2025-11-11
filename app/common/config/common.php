<?php
/**
 * common.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */

use yii\db\Connection;
use yii\caching\DummyCache;
use yii\db\mysql\Schema as MysqSchema;
use yii\db\pgsql\Schema as PgsqlSchema;
use yii\log\FileTarget;
use yii\log\SyslogTarget;
use yii\redis\Connection as RedisConnection;
use yii\redis\Cache as RedisCache;
use yii\caching\CacheInterface;
use fractalCms\Module as FractalCmsModule;
$config = [
    'sourceLanguage' => 'fr',
    'language' => 'fr',
    'timezone' => 'Europe/Paris',
    'extensions' => require_once dirname(__DIR__, 3) . '/vendor/yiisoft/extensions.php',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@approot' => dirname(__DIR__, 2),
        '@app' => dirname(__DIR__) . '/',
        '@webapp' => dirname(__DIR__, 2) . '/webapp',
        '@console' => dirname(__DIR__, 2) . '/console',
        '@data' => dirname(__DIR__, 2) . '/data',
        '@modules' => dirname(__DIR__, 2) . '/modules',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',

    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'version' => getstrenv('APP_VERSION'),
    'container' => [
        'definitions' => [
        ],
        'singletons' => [
            CacheInterface::class => DummyCache::class,
            Connection::class => [
                'charset' => 'utf8',
                'dsn' => getstrenv('DB_DRIVER').':host=' . getstrenv('DB_HOST') . ';port=' . getstrenv('DB_PORT') . ';dbname=' . getstrenv('DB_DATABASE'),
                'username' => getstrenv('DB_USER'),
                'password' => getstrenv('DB_PASSWORD'),
                'tablePrefix' => getstrenv('DB_TABLE_PREFIX'),
                'enableSchemaCache' => getboolenv('DB_SCHEMA_CACHE'),
                'schemaCacheDuration' => getintenv('DB_SCHEMA_CACHE_DURATION'),
            ],
        ]
    ],
    'bootstrap' => [
        'log',
        'fractal-cms'
    ],
    'modules' => [
        'fractal-cms' => [
            'class' => FractalCmsModule::class,
            'viewItemPath'=> '@webapp/views/fractal-cms'
        ]
    ],
    'components' => [
        'db' => Connection::class,
        'cache' => CacheInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => YII_DEBUG ? ['error', 'warning', 'profile']:['error', 'warning'],
                    'maskVars' => [
                        '_SERVER.HTTP_AUTHORIZATION',
                        '_SERVER.PHP_AUTH_USER',
                        '_SERVER.PHP_AUTH_PW',
                        '_SERVER.DB_PASSWORD',
                        '_SERVER.DB_ROOT_PASSWORD',
                        '_SERVER.REDIS_PASSWORD',
                        '_SERVER.PROFIDEO_PASSWORD',
                        '_SERVER.FILESYSTEM_S3_SECRET',
                    ],
                ],
            ],
        ],
        'authManager' => [
            'class'=>'yii\rbac\DbManager',
            'db'=>'db',
            'itemTable'=>'{{%authItem}}',
            'itemChildTable'=>'{{%authItemChild}}',
            'assignmentTable'=>'{{%authAssignment}}',
            'ruleTable' => '{{%authRules}}'
        ],
    ],
    'params' => [
    ],
];

if (getstrenv('DB_DRIVER') === 'pgsql') {
    $config['container']['singletons'][Connection::class]['schemaMap'] = [
        getstrenv('DB_DRIVER') => [
            'class' => getstrenv('DB_DRIVER') === 'pgsql' ? PgsqlSchema::class : MysqSchema::class,
            'defaultSchema' => getstrenv('DB_SCHEMA')
        ]
    ];
}

if (getboolenv('SYSLOG_ENABLED') === true) {
    $config['components']['log']['targets'][] = [
        'class' => SyslogTarget::class,
        'enabled' => getboolenv('SYSLOG_ENABLED'),
        'levels' => YII_DEBUG ? ['error', 'warning', 'profile']:['error', 'warning'],
        'identity' => getstrenv('SYSLOG_IDENTITY'),
        'maskVars' => [
            '_SERVER.HTTP_AUTHORIZATION',
            '_SERVER.PHP_AUTH_USER',
            '_SERVER.PHP_AUTH_PW',
            '_SERVER.DB_USER',
            '_SERVER.DB_DATABASE',
            '_SERVER.DB_PASSWORD',
            '_SERVER.DB_HOST',
            '_SERVER.DB_ROOT_PASSWORD',
            '_SERVER.REDIS_PASSWORD',
            '_SERVER.FILESYSTEM_S3_SECRET',
        ],
    ];
}
if (getboolenv('REDIS_ENABLED')) {
    $config['container']['singletons'][RedisConnection::class] = [
        'class' => RedisConnection::class,
        'hostname' => getstrenv('REDIS_HOST'),
        'port' => getintenv('REDIS_PORT'),
        'database' => getintenv('REDIS_DATABASE'),
    ];
    $password = getstrenv('REDIS_PASSWORD');
    if (empty($password) === false) {
        $config['container']['singletons'][RedisConnection::class]['password'] = $password;
    }

    $config['container']['singletons'][CacheInterface::class] = [
        'class' => RedisCache::class,
        'redis' => RedisConnection::class
    ];
    $config['components']['redis'] = RedisConnection::class;

}
return $config;
