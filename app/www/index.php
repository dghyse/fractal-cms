<?php
/**
 * index.php
 *
 * PHP Version 8.2+
 *
 * @version XXX
 * @package www
 */

use yii\web\Application;

// init autoloaders
require_once dirname(__DIR__, 2).'/vendor/autoload.php';

require_once dirname(__DIR__, ).'/common/config/bootstrap.php';

require_once dirname(__DIR__, 2).'/vendor/yiisoft/yii2/Yii.php';

$config = require_once dirname(__DIR__).'/webapp/config/main.php';

(new Application($config))->run();
