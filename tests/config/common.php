<?php
/**
 * common.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */

use yii\db\Connection;
use yii\caching\DummyCache;
use yii\log\FileTarget;
use yii\caching\CacheInterface;
use fractalCms\Module as FractalCmsModule;
$config = [
    'id' => 'dghyse/fractal-cms',
    'sourceLanguage' => 'fr',
    'language' => 'fr',
    'timezone' => 'Europe/Paris',
    'extensions' => [],
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
    'version' => '1.6.2',
    'container' => [
        'singletons' => [
            CacheInterface::class => DummyCache::class,
            Connection::class => [
                'charset' => 'utf8',
                'dsn' => 'mysql:host=localhost;port=3306;dbname=fractalcms_test',
                'username' => 'dghyse',
                'password' => 'redcat',
                'tablePrefix' => '',
                'enableSchemaCache' => true,
                'schemaCacheDuration' => 3600,
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
        ]
    ],
    'params' => [
    ],
];
return $config;
