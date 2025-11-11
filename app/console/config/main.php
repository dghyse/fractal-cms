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

use yii\console\controllers\MigrateController;
$config = require dirname(__DIR__, 2).'/common/config/common.php';

$config['basePath'] = dirname(__DIR__);
$config['id'] = 'dghyse/site-fractal-cms';
$config['name'] = 'Fractal CMS';
$config['aliases']['@webroot'] = dirname(__DIR__, 2).'/www';

$config['controllerNamespace'] = 'console\controllers';

$config['controllerMap'] = [
    'migrate' => [
        'class' => MigrateController::class,
        'migrationNamespaces' => [
            'console\\migrations',
        ],
        'migrationPath' => [
            '@yii/rbac/migrations'
        ]
    ],
];
return $config;
